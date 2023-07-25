<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SalaryAdvance;
use App\Models\SalaryAdvanceOfPay;

class SalaryAdvanceController extends Controller
{
    public function list(int $salary_id)
    {
        $salaryadvances = SalaryAdvance::where('salary_id', $salary_id)->get();
        $salaryadvanceofpays = SalaryAdvanceOfPay::select('salary_advance_of_pays.*', 'ca.razonsocial', 'p.year_month', 'p.amount')
            ->join('payments AS p', 'p.id', 'payment_id')
            ->join('cliente_auditwholes AS ca', 'ruc', 'cliente_auditwhole_ruc')
            ->where('salary_id', $salary_id)->get();

        return response()->json([
            'salaryadvances' => $salaryadvances,
            'salaryadvanceofpays' => $salaryadvanceofpays
        ]);
    }

    public function store(Request $request)
    {
        $salaryadvance = SalaryAdvance::create($request->all());

        return response()->json(['salaryadvance' => $salaryadvance]);
    }

    public function update(Request $request, int $id)
    {
        $salaryadvance = SalaryAdvance::find($id);
        $salaryadvance->update($request->only(['amount', 'description', 'date']));

        return response()->json(['salaryadvance' => $salaryadvance]);
    }

    public function destroy(int $id)
    {
        $salaryadvance = SalaryAdvance::find($id);
        $salaryadvance->delete();
    }
}
