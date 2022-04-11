<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use Ecotone\Modelling\CommandBus;
use Illuminate\Http\Request;

class IssueController extends Controller
{
    public function report(Request $request, CommandBus $commandBus)
    {
        $issueId = $commandBus->sendWithRouting(Issue::REPORT_ISSUE, $request->all());

        return view('done', ["id" => $issueId, "domain" => $request->root()]);
    }
}
