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

        return view('done', ["id" => $issueId]);
    }

    public function close(CommandBus $commandBus)
    {
        $commandBus->sendWithRouting(Issue::CLOSE_ISSUE, metadata: ["aggregate.id" => request('id')]);

        return view('closed');
    }
}
