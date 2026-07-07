<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConsultationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConsultationRequestController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $requests = ConsultationRequest::query()
            ->when($status, fn ($query, $value) => $query->where('status', $value))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $pendingCount = ConsultationRequest::pending()->count();

        return view('admin.consultation-requests.index', compact('requests', 'pendingCount', 'status'));
    }

    public function show(ConsultationRequest $consultationRequest): View
    {
        return view('admin.consultation-requests.show', [
            'consultationRequest' => $consultationRequest,
        ]);
    }

    public function updateStatus(Request $request, ConsultationRequest $consultationRequest): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,processed'],
        ]);

        $consultationRequest->update(['status' => $validated['status']]);

        return back()->with('success', 'Đã cập nhật trạng thái yêu cầu tư vấn.');
    }

    public function destroy(ConsultationRequest $consultationRequest): RedirectResponse
    {
        $consultationRequest->delete();

        return redirect()
            ->route('admin.consultation-requests.index')
            ->with('success', 'Đã xóa yêu cầu tư vấn.');
    }
}
