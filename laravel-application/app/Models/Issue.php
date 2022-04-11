<?php

namespace App\Models;

use App\Models\Command\ReportIssue;
use App\Models\Event\IssueWasReported;
use Ecotone\Modelling\Attribute\Aggregate;
use Ecotone\Modelling\Attribute\AggregateIdentifier;
use Ecotone\Modelling\Attribute\AggregateIdentifierMethod;
use Ecotone\Modelling\Attribute\CommandHandler;
use Ecotone\Modelling\WithAggregateEvents;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

#[Aggregate]
final class Issue extends Model
{
    use WithAggregateEvents;

    const REPORT_ISSUE = "issue.report";

    #[CommandHandler(Issue::REPORT_ISSUE)]
    public static function reportNew(ReportIssue $command): self
    {
        $issue = new self();
        $issue->email = $command->email->address;
        $issue->ongoing = true;
        $issue->content = $command->content;
        $issue->ticketId = Uuid::uuid4()->toString();
        $issue->save();

        $issue->recordThat(new IssueWasReported($issue->id));

        return $issue;
    }

    #[AggregateIdentifierMethod("id")]
    public function getId(): ?int
    {
        return $this->id;
    }
}
