<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SummaryController extends Controller
{
    public function index(Request $request)
    {
        $payments = DB::table('payments')->select(DB::raw("SUM(amount) as amount"))
            ->whereMonth('date', substr($request->month, 5))
            ->whereYear('date', substr($request->month, 0, 4))
            ->first();

        $salary = DB::table('salaries')->select(DB::raw('SUM(amount_cheque + cash) as amount'))
            ->whereMonth('date', substr($request->month, 5))
            ->whereYear('date', substr($request->month, 0, 4))
            ->first();

        $salaryadvance = DB::table('salary_advances')->select(DB::raw('SUM(amount) as amount'))
            ->whereMonth('date', substr($request->month, 5))
            ->whereYear('date', substr($request->month, 0, 4))
            ->first();

        $salaryadvancepays = DB::table('salary_advance_of_pays')->select(DB::raw('SUM(amount) as amount'))
            ->whereMonth('date', substr($request->month, 5))
            ->whereYear('date', substr($request->month, 0, 4))
            ->first();

        $expense = DB::table('expense_items')->select(DB::raw('SUM(amount) as amount'))
            ->whereMonth('date', substr($request->month, 5))
            ->whereYear('date', substr($request->month, 0, 4))
            ->first();

        return response()->json([
            'ingress' => number_format($payments->amount, 2, ',', '.'),
            'bad' => number_format($payments->amount * .01, 2, ',', '.'),
            'salary' => number_format($salary->amount + $salaryadvance->amount + $salaryadvancepays->amount, 2, ',', '.'),
            'expense' => number_format($expense->amount, 2, ',', '.'),
            'spare' => number_format($payments->amount - ($payments->amount * .01) - ($salary->amount + $salaryadvance->amount + $salaryadvancepays->amount) - $expense->amount, 2, ',', '.')
        ]);
    }
}
