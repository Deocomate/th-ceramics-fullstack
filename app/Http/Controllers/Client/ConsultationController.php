<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreConsultationRequest;
use App\Mail\ConsultationConfirmationMail;
use App\Mail\ConsultationRequestedMail;
use App\Models\ConsultationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class ConsultationController extends Controller
{
    public function store(StoreConsultationRequest $request): JsonResponse
    {
        $record = ConsultationRequest::create($request->validated());

        Mail::to(config('mail.contact_email', 'gshaithanh@gmail.com'))
            ->queue(new ConsultationRequestedMail($record));

        if ($record->email) {
            Mail::to($record->email)->queue(new ConsultationConfirmationMail($record));
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Yêu cầu của bạn đã được ghi nhận. Thanh Hải sẽ liên hệ sớm nhất!',
        ]);
    }
}
