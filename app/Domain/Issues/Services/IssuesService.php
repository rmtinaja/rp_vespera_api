<?php

namespace App\Domain\Issues\Services;

use Illuminate\Support\Str;
use App\Domain\Issues\DTO\CreateIssuesDTO;
use App\Domain\Issues\Repositories\IssuesRepository;
use Carbon\Carbon;

class IssuesService
{
    public function __construct(
        protected IssuesRepository $repository
    ) {}

    public function list()
    {
        return $this->repository->getAll();
    }

    public function create(array $data)
    {
        $dto = new CreateIssuesDTO(
            issue_id: (string) Str::uuid(),
            concern_title: $data['concern_title'],
            concern_description: $data['concern_description'],
            assignee_id: $data['assignee_id'],
            status_priority: $data['status_priority'],
            date_issues: Carbon::now(),
            date_start: $data['date_start'] ?? null,
            date_hold: $data['date_hold'] ?? null,
            date_complete: $data['date_complete'] ?? null,
            date_resume: $data['date_resume'] ?? null,
            ticket_status: $data['ticket_status'] ?? null,
            ticket_no: $data['ticket_no'] ?? null,
            created_by:1,
        );

        return $this->repository->create($dto);
    }

    public function update(string $issueId, array $data)
    {
        $issue = $this->repository->find($issueId);
        return $this->repository->update($issue, $data);
    }

    public function delete(string $issueId)
    {
        $issue = $this->repository->find($issueId);
        $this->repository->delete($issue);
    }
}
