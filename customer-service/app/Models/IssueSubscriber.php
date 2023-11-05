<?php

namespace App\Models;

use App\Infrastructure\EcotoneConfiguration;
use App\Mail\ClosedIssueMail;
use App\Mail\ConfirmReportedIssueMail;
use App\Models\Event\IssueWasClosed;
use App\Models\Event\IssueWasReported;
use Ecotone\Messaging\Attribute\Asynchronous;
use Ecotone\Messaging\Attribute\Parameter\Header;
use Ecotone\Modelling\Attribute\EventHandler;
use Ecotone\Modelling\DistributedBus;

class IssueSubscriber
{
    #[Asynchronous(EcotoneConfiguration::NOTIFICATIONS_CHANNEL)]
    #[EventHandler(endpointId: "confirmReceivedIssueNotification")]
    public function sendNotificationToConfirmReceivedIssue(IssueWasReported $event, #[Header("ecotone.dlq.message_replied")] ?string $wasReplied): void
    {
        $issue = Issue::find($event->issueId);

// This is just for demo purposes. The first time email is sent, it will fail and will be delivered to DLQ
// after replaying it from Ecotone Pulse, it will be handled correctly.
        if (is_null($wasReplied)) {
            throw new \InvalidArgumentException("Can't render template, missing issue id parameter");
        }

        \Mail::to($issue->email)->send(new ConfirmReportedIssueMail($event->issueId));
    }

    #[Asynchronous(EcotoneConfiguration::NOTIFICATIONS_CHANNEL)]
    #[EventHandler(endpointId: "doneIssueNotification")]
    public function sendNotificationAboutClosedIssue(IssueWasClosed $event): void
    {
        $issue = Issue::find($event->issueId);

        \Mail::to($issue->email)->send(new ClosedIssueMail($event->issueId));
    }

    #[Asynchronous(EcotoneConfiguration::NOTIFICATIONS_CHANNEL)]
    #[EventHandler(endpointId: 'createTicketInBackofficeService')]
    public function createTicketInBackofficeService(IssueWasReported $event, DistributedBus $distributedBus): void
    {
        $issue = Issue::find($event->issueId);

        $distributedBus->convertAndSendCommand(
            "backoffice_service",
            "ticket.prepareTicket",
            [
                "ticketId" => $issue->ticketId,
                "ticketType" => "customer-issue",
                "description" => $issue->content
            ]
        );
    }

    #[EventHandler]
    public function closeTicketInBackofficeService(IssueWasClosed $event, DistributedBus $distributedBus): void
    {
        $issue = Issue::find($event->issueId);

        $distributedBus->convertAndSendCommand(
            "backoffice_service",
            "ticket.cancel",
            [
                "ticketId" => $issue->ticketId
            ]
        );
    }
}
