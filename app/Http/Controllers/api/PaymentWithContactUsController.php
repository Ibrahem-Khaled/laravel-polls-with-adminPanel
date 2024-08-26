<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentWithContactUsController extends Controller
{
    public function storeContactUs(Request $request)
    {
        $user = auth()->guard('api')->user();
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/contact_us'), $filename);
            $imagePath = asset('uploads/contact_us/' . $filename); // Storing the full public URL
        }

        $contactUs = ContactUs::create([
            'user_id' => $user->id,
            'subject' => $request->subject,
            'message' => $request->message,
            'image' => $imagePath,
        ]);

        return response()->json([
            'message' => 'Contact message successfully submitted.',
            'data' => $contactUs,
        ], 201);
    }

    public function payment()
    {
        $user = auth()->guard('api')->user();
        $payments = $user->payments;
        return response()->json($payments, 200);
    }

    public function storePayment(Request $request)
    {
        $user = auth()->guard('api')->user();
        Payment::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'method' => $request->method,
            'transaction' => $request->transaction,
        ]);
        $user->update(['balance' => $user->balance - $request->amount]);
        return response()->json(['message' => 'Payment successfully submitted.'], 200);
    }


}
