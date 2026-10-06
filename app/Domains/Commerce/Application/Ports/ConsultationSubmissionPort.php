<?php

namespace App\Domains\Commerce\Application\Ports;

interface ConsultationSubmissionPort
{
    public function submit(array $data): void;
}
