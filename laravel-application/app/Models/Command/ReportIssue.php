<?php

namespace App\Models\Command;

use App\Models\Email;

class ReportIssue
{
    public readonly Email $email;
    public readonly string $content;
}
