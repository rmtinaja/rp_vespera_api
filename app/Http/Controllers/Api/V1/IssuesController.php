<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Domain\Issues\Services\IssuesService;
use Illuminate\Http\Request;

class IssuesController extends Controller
{
    public function __construct(
        protected IssuesService $service
    ) {}

    public function index()
    {
        return response()->json(
            $this->service->list()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'concern_title'       => 'required|string',
            'concern_description' => 'required|string',
            'assignee_id'         => 'required|integer',
            'status_priority'     => 'required|string',
            'ticket_status'       => 'nullable|string',
            'ticket_no'           => 'nullable|string',
            'date_issues'         => 'nullable|date',
            'date_start'          => 'nullable|date',
            'date_hold'           => 'nullable|date',
            'date_complete'       => 'nullable|date',
            'date_resume'         => 'nullable|date',
        ]);

        return response()->json(
            $this->service->create($data),
            201
        );
    }

    public function update(Request $request, string $issueId)
    {
        return response()->json(
            $this->service->update($issueId, $request->all())
        );
    }

    public function destroy(string $issueId)
    {
        $this->service->delete($issueId);
        return response()->json(['message' => 'Issue deleted']);
    }
}
