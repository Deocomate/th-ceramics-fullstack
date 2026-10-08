<?php

namespace App\Domains\Commerce\Application;

use App\Domains\Commerce\Application\Ports\ConsultationSubmissionPort;

class ConsultationService
{
    public function __construct(private readonly ConsultationSubmissionPort $submissions) {}

    public function submit(array $data): void
    {
        $this->submissions->submit($data);
    }
}
