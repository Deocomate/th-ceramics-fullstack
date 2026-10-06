<?php

namespace App\Domains\Commerce\Http\Admin;

use App\Domains\Commerce\Application\ConsultationRequestService;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConsultationRequestController extends Controller
{
    public function index(Request $request, ConsultationRequestService $consultations): View
    {
        $status = $request->query('status');
        ['requests' => $requests, 'pendingCount' => $pendingCount] = $consultations->listing($status);

        return view('admin.commerce.consultation-requests.index', compact('requests', 'pendingCount', 'status'));
    }

    public function show(int $consultationRequest, ConsultationRequestService $consultations): View
    {
        return view('admin.commerce.consultation-requests.show', [
            'consultationRequest' => $consultations->find($consultationRequest),
        ]);
    }

    public function updateStatus(Request $request, int $consultationRequest, ConsultationRequestService $consultations): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,processed'],
        ]);

        $consultations->updateStatus($consultationRequest, $validated['status']);

        return back()->with('success', 'Đã cập nhật trạng thái yêu cầu tư vấn.');
    }

    public function destroy(int $consultationRequest, ConsultationRequestService $consultations): RedirectResponse
    {
        $consultations->delete($consultationRequest);

        return redirect()
            ->route('admin.consultation-requests.index')
            ->with('success', 'Đã xóa yêu cầu tư vấn.');
    }
}
