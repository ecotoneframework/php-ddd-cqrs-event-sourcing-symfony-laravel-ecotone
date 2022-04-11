<?php

namespace App\Models\Event;

class IssueWasReported
{
    public function __construct(public readonly int $issueId) {}
}
