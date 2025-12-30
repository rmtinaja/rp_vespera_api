<?php

namespace App\Domain\Issues\Repositories;
use App\Domain\Issues\Models\Issues;
use App\Domain\Issues\DTO\CreateIssuesDTO;
class IssuesRepository
{
   public function getAll()
    {
        return Issues::where('is_active', true)->get();
    }

    public function find(string $issueId): ?Issues
    {
        return Issues::where('issue_id', $issueId)->first();
    }

    public function create(CreateIssuesDTO $dto): Issues
    {
        return Issues::create([
            'issue_id'            => $dto->issue_id,
            'concern_title'       => $dto->concern_title,
            'concern_description' => $dto->concern_description,
            'assignee_id'         => $dto->assignee_id,
            'status_priority'     => $dto->status_priority,
            'date_issues'         => $dto->date_issues,
            'date_start'          => $dto->date_start,
            'date_hold'           => $dto->date_hold,
            'date_complete'       => $dto->date_complete,
            'created_by'          => $dto->created_by,
            'date_created'        => now(),
        ]);
    }

    public function update(Issues $issue, array $data): Issues
    {
        $issue->update($data);
        return $issue;
    }

    public function delete(Issues $issue): void
    {
        $issue->update(['is_active' => false]);
    }
}