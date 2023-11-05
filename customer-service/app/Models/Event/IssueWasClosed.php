<?php

namespace App\Models\Event;

final readonly class IssueWasClosed
{
    public function __construct(public int $issueId) {}
}
