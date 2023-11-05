<?php

namespace App\Models\Event;

final readonly class IssueWasReported
{
    public function __construct(public int $issueId) {}
}
