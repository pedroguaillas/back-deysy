<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResources;
use App\Models\Payment;
use App\Models\SalaryAdvanceOfPay;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\PDF;

class UserController extends Controller
{
    public function listusser(Request $request)
    {
        $search = '';
        $paginate = 15;

        if ($request) {
            $search = $request->search;
            $paginate = $request->has('paginate') ? $request->paginate : $paginate;
        }

        $users = User::where('rol', 'asesor')
            ->where('name', 'LIKE', "%$search%")
            ->orderBy('created_at', 'DESC');

        return UserResources::collection($users->paginate($paginate));
    }

    public function listusersalaries(Request $request)
    {
        $search = '';
        $paginate = 15;

        if ($request) {
            $search = $request->search;
            $paginate = $request->has('paginate') ? $request->paginate : $paginate;
        }

        $users = User::where('name', 'LIKE', "%$search%")
            ->where('salary', '>', 0)
            ->orderBy('created_at', 'DESC');

        return UserResources::collection($users->paginate($paginate));
    }

    public function show(int $id)
    {
        $user = User::find($id);
        return response()->json(['user' => $user]);
    }

    public function update(int $id, Request $request)
    {
        $user = User::find($id);
        $user->update($request->all());

        return response()->json(['user' => $user]);
    }

    // Lista de clientes que pagan
    public function customers(int $id, Request $request)
    {
        $user = User::find($id);

        $customers = DB::table('cliente_auditwholes')
            // ->select('razonsocial', 'ruc', DB::raw("(SELECT SUM(amount) FROM payments WHERE cliente_auditwhole_ruc = ruc AND year = $request->year) AS total"))
            ->select('razonsocial', 'ruc', DB::raw("(SELECT SUM(amount) FROM payments WHERE cliente_auditwhole_ruc = ruc) AS total"))
            ->where('user_id', $id)
            ->where('amount', '>', 0)
            ->orderBy('razonsocial')
            ->get();

        return response()->json(['user' => $user, 'customers' => $customers]);
    }

    public function customerpdf($id)
    {
        $user = User::find($id);

        $months = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];

        // $query = "(SELECT SUM(amount) FROM payments WHERE cliente_auditwhole_ruc = ruc AND year = 2022) AS total";
        $query = "(SELECT SUM(amount) FROM payments WHERE cliente_auditwhole_ruc = ruc AND `year_month` LIKE '2023-%') AS total";

        foreach ($months as $key => $value) {
            $query .= ",(SELECT amount FROM payments WHERE cliente_auditwhole_ruc = ruc AND `year_month` = CONCAT('2023-', LPAD($key, 2, '0'))) AS $value";
            // $query .= ",(SELECT amount FROM payments WHERE cliente_auditwhole_ruc = ruc AND month = $key AND year = 2022) AS $value";
        }

        $customers = DB::table('cliente_auditwholes')
            ->select('razonsocial', DB::raw($query))
            ->where('user_id', $id)
            ->where('amount', '>', 0)
            ->orderBy('razonsocial')
            ->get();

        $pdf = PDF::loadView('pdf', compact('user', 'months', 'customers'))->setPaper('a4', 'landscape');

        return $pdf->stream();
    }

    // Lista de clientes que tienen pagos con cruce habiles
    public function customerswidthcross(int $id)
    {
        $customers = DB::table('cliente_auditwholes')
            ->select('RUC', 'razonsocial')
            // Clientes solo de este asesor
            ->where('user_id', $id)
            // El cliente se encuentren en la tabla pagos
            ->whereIn('RUC', function ($query) {
                $query->select('cliente_auditwhole_ruc')
                    ->from(with(new Payment())->getTable())
                    // Solo pagos con Cruce
                    ->where('type', 'Cruce')
                    // Y este cruce no se encuentre en
                    ->whereNotIn('id', function ($query) {
                        $query->select('payment_id')
                            ->from(with(new SalaryAdvanceOfPay())->getTable());
                    });
            })
            ->get();

        return response()->json(['customers' => $customers]);
    }
}
