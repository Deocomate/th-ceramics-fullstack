<?php

namespace App\Domains\Commerce\Application;

use App\Domains\Commerce\Application\Ports\ConsultationRequestRepositoryPort;

class ConsultationRequestService
{
    public function __construct(private readonly ConsultationRequestRepositoryPort $requests) {}

    public function listing(?string $status): array
    {
        return $this->requests->listing($status);
    }

    public function find(int $id): mixed
    {
        return $this->requests->find($id);
    }

    public function updateStatus(int $id, string $status): void
    {
        $this->requests->updateStatus($id, $status);
    }

    public function delete(int $id): void
    {
        $this->requests->delete($id);
    }
}
