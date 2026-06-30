<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(): View
    {
        $payments = Payment::with(['appointment.doctor', 'appointment.patient'])
            ->latest()
            ->paginate(20);

        $totalApproved = Payment::where('status', Payment::STATUS_APPROVED)->sum('amount_cop');

        return view('admin.payments.index', compact('payments', 'totalApproved'));
    }
}
