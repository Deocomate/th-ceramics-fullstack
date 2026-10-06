<?php

namespace App\Domains\Commerce\Http\Client;

use App\Domains\Commerce\Application\ConsultationService;
use App\Domains\Commerce\Http\Requests\Client\StoreConsultationRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ConsultationController extends Controller
{
    public function store(StoreConsultationRequest $request, ConsultationService $consultations): JsonResponse
    {
        $consultations->submit($request->validated());

        return response()->json([
            'status' => 'success',
            'message' => 'Yêu cầu của bạn đã được ghi nhận. Thanh Hải sẽ liên hệ sớm nhất!',
        ]);
    }
}
