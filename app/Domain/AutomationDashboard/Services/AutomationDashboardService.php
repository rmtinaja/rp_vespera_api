<?php

namespace App\Domain\AutomationDashboard\Services;

use App\Domain\AutomationDashboard\DTO\CreateAutomationDashboardDTO;
use App\Domain\AutomationDashboard\Repositories\AutomationDashboardRepository;
use Carbon\Carbon;

class AutomationDashboardService
{
    public function __construct(
        protected AutomationDashboardRepository $repository
    ) {}

    public function list()
    {
        return $this->repository->getAll();
    }
    public function create(array $data)
    {
        $dto = new CreateAutomationDashboardDTO(
            customer_psid: $data['customer_psid'],
            conversation_status: $data['conversation_status'],
            conversation_updated_from: $data['conversation_updated_from'],
            conversation_updated_to: $data['conversation_updated_to'],
            created_by:1,
        );

        return $this->repository->create($dto);
    }

    public function update(int $conversation_id, array $data)
    {
        $conversation = $this->repository->find($conversation_id);
        return $this->repository->update($conversation, $data);
    }

    public function delete(int $conversation_id)
    {
        $conversation = $this->repository->find($conversation_id);
        $this->repository->delete($conversation);
    }
}