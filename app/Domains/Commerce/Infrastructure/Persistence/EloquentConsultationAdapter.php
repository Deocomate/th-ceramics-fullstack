<?php

namespace App\Domains\Commerce\Infrastructure\Persistence;

use App\Domains\Commerce\Application\Ports\ConsultationRequestRepositoryPort;
use App\Domains\Commerce\Application\Ports\ConsultationSubmissionPort;
use App\Domains\Commerce\Infrastructure\Mail\ConsultationConfirmationMail;
use App\Domains\Commerce\Infrastructure\Mail\ConsultationRequestedMail;
use App\Domains\Commerce\Infrastructure\Models\ConsultationRequest;
use Illuminate\Support\Facades\Mail;

class EloquentConsultationAdapter implements ConsultationRequestRepositoryPort, ConsultationSubmissionPort
{
    public function submit(array $data): void
    {
        $record = ConsultationRequest::create($data);

        Mail::to(config('mail.contact_email', 'gshaithanh@gmail.com'))
            ->queue(new ConsultationRequestedMail($record));

        if ($record->email) {
            Mail::to($record->email)->queue(new ConsultationConfirmationMail($record));
        }
    }

    public function listing(?string $status): array
    {
        $requests = ConsultationRequest::query()
            ->when($status, fn ($query, $value) => $query->where('status', $value))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return ['requests' => $requests, 'pendingCount' => ConsultationRequest::pending()->count()];
    }

    public function find(int $id): mixed
    {
        return ConsultationRequest::findOrFail($id);
    }

    public function updateStatus(int $id, string $status): void
    {
        ConsultationRequest::query()->findOrFail($id)->update(['status' => $status]);
    }

    public function delete(int $id): void
    {
        ConsultationRequest::query()->findOrFail($id)->delete();
    }
}
