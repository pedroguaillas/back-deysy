<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\SalaryResources;
use App\Models\Salary;
use App\Models\SalaryAdvance;
use App\Models\SalaryAdvanceOfPay;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SalaryController extends Controller
{
    public function salarylist(Request $request)
    {
        $user_id = null;
        $paginate = 15;

        if ($request) {
            $user_id = $request->user_id;
            $paginate = $request->has('paginate') ? $request->paginate : $paginate;
        }

        $salaries = Salary::select(
            'salaries.id',
            'user_id',
            'month',
            'salaries.amount',
            'cheque',
            'amount_cheque',
            'balance',
            'cash',
            DB::raw('(SELECT SUM(amount) FROM salary_advances AS sa WHERE salaries.id = sa.salary_id) AS paid'),
            DB::raw('(SELECT SUM(amount) FROM salary_advance_of_pays AS saop WHERE salaries.id = saop.salary_id) AS paidsoap')
        )
            ->where('user_id', $user_id)
            ->groupBy('salaries.id', 'user_id', 'month', 'salaries.amount', 'cheque', 'amount_cheque', 'balance', 'cash')
            ->orderBy('month', 'DESC');

        return response()->json([
            'salaries' => SalaryResources::collection($salaries->paginate($paginate)),
            'user' => User::find($user_id)
        ]);
    }

    public function store(Request $request)
    {
        $salary = Salary::where([
            'user_id' => $request->user_id,
            'month' => $request->month
        ])->get();

        if (count($salary) > 0) {
            return response()->json(['msm' => 'Ya existe sueldo de ese mes'], 405);
        }

        $salary = Salary::create($request->all());

        return response()->json(['salary' => $salary]);
    }

    public function update(Request $request, $id)
    {
        $salary = Salary::find($id);
        $salary->update($request->all());

        return response()->json(['salary' => $salary]);
    }

    public function destroy($id)
    {
        $salary = Salary::find($id);
        SalaryAdvance::where('salary_id', $id)->delete();
        SalaryAdvanceOfPay::where('salary_id', $id)->delete();
        $salary->delete();
    }
}
