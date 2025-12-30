<?php

namespace App\Domain\Issues\DTO;

class CreateIssuesDTO
{
    public function __construct(
        public string $issue_id,
        public string $concern_title,
        public string $concern_description,
        public int $assignee_id,
        public string $status_priority,
        public ?string $date_issues,
        public ?string $date_start,
        public ?string $date_hold,
        public ?string $date_complete,
        public int $created_by,
    ) {}
}