<?php

namespace App\Domains\Commerce\Application\Ports;

interface ConsultationRequestRepositoryPort
{
    public function listing(?string $status): array;

    public function find(int $id): mixed;

    public function updateStatus(int $id, string $status): void;

    public function delete(int $id): void;
}
