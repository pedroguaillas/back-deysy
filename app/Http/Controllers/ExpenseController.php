<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::all();

        return response()->json(['expenses' => $expenses]);
    }

    private function validar(Request $request)
    {
        $this->validate($request, [
            'description' => 'required|min:3|max:300',
            'amount' => 'required|decimal:0,2',
        ], [
            'description' => [
                'required' => 'La descripción es requerido',
                'min' => 'La descripción debe tener minimo 3 letras',
                'max' => 'La descripción es muy largo'
            ],
            'amount' => [
                'required' => 'El monto es requerido',
                'decimal' => 'El monto debe tener hasta 2 decimales'
            ],
        ]);
    }

    public function store(Request $request)
    {
        $this->validar($request);

        $expense = Expense::create($request->all());

        return response()->json(['expense' => $expense]);
    }

    public function update(Request $request, $id)
    {
        $this->validar($request);
        $expense = Expense::findOrFail($id);
        $expense->update($request->all());

        return response()->json(['expense' => $expense]);
    }

    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();
    }
}
