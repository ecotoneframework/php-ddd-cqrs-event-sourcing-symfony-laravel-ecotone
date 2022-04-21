<?php

namespace App\Models\Event;

final class IssueWasClosed
{
    public function __construct(public readonly int $issueId) {}
}
