<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalaryAdvanceOfPay;

class SalaryAdvanceOfPayController extends Controller
{
    public function store(Request $request)
    {
        $salaryadvanceofpay = SalaryAdvanceOfPay::create($request->all());

        return response()->json(['salaryadvanceofpay' => $salaryadvanceofpay]);
    }

    public function update(Request $request, int $id)
    {
        $salaryadvanceofpay = SalaryAdvanceOfPay::find($id);
        $salaryadvanceofpay->update($request->only(['amount', 'description', 'payment_id', 'date']));

        return response()->json(['salaryadvanceofpay' => $salaryadvanceofpay]);
    }

    public function destroy(int $id)
    {
        $salaryadvanceofpay = SalaryAdvanceOfPay::find($id);
        $salaryadvanceofpay->delete();
    }
}
