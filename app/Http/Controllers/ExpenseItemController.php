<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ExpenseItemController extends Controller
{
    public function index($id)
    {
        $expense = Expense::find($id);
        $expenseItems = ExpenseItem::where('expense_id', $expense->id)
            ->orderByDesc('month')
            ->get();

        return response()->json([
            'expense' => $expense,
            'expenseItems' => $expenseItems
        ]);
    }

    private function validar(Request $request)
    {
        $this->validate($request, [
            'month' => ['required', Rule::unique('expense_items')->where(function ($query) use ($request) {
                return $query->where('expense_id', $request->expense_id)
                    ->where('month', $request->month);
            })],
            'amount' => 'required|decimal:0,2',
            'pay_method' => 'required',
            'date' => 'required'
        ], [
            'month' => [
                'required' => 'El mes es requerido',
                'unique' => 'No se puede realizar dos pagos el mismo mes'
            ],
            'amount' => [
                'required' => 'El monto es requerido',
                'decimal' => 'El monto debe tener hasta 2 decimales'
            ],
            'pay_method.required' => 'Debe seleccionar la forma de pago',
            'date.required' => 'Debe seleccionar la fecha'
        ]);
    }

    public function store(Request $request)
    {
        $this->validar($request);

        $expenseItem = ExpenseItem::create($request->all());

        return response()->json(['expenseItem' => $expenseItem]);
    }

    public function update(Request $request, $id)
    {
        // $this->validar($request);
        $expenseItem = ExpenseItem::findOrFail($id);
        $expenseItem->update($request->all());

        return response()->json(['expenseItem' => $expenseItem]);
    }

    public function destroy($id)
    {
        $expenseItem = ExpenseItem::findOrFail($id);
        $expenseItem->delete();
    }
}
