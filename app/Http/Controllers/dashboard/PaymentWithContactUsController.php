<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentWithContactUsController extends Controller
{
    public function contactUs(Request $request)
    {
        $status = $request->input('status', 'pending');
        $contacts = ContactUs::where('status', $status)->get();

        return view('dashboard.contact-us', compact('contacts', 'status'));
    }

    public function payment(Request $request)
    {
        $status = $request->input('status', 'unpaid');
        $payments = Payment::where('status', $status)->get();
        return view('dashboard.payment', compact('payments', 'status'));
    }

    public function updateStatusPayment(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->status = 'paid';
        $payment->save();

        return redirect()->back()->with('success', 'Payment status updated successfully.');
    }
}
