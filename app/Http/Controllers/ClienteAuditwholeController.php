<?php

namespace App\Http\Controllers;

use App\Models\ClienteAuditwhole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ClienteAuditwholeResources;
use App\Models\Payment;
use App\Models\User;

class ClienteAuditwholeController extends Controller
{
    public function customerlist(Request $request)
    {
        $search = '';
        $paginate = 15;
        $user_id = 0;

        if ($request) {
            $search = $request->search;
            $paginate = $request->has('paginate') ? $request->paginate : $paginate;
            $user_id = $request->has('user_id') ? $request->user_id : $user_id;
        }

        if ($user_id > 0) {
            $customers = ClienteAuditwhole::join('users', 'id', 'user_id')
                ->select('ruc', 'razonsocial', 'name', 'amount')
                ->where([
                    'rol' => 'asesor',
                    'users.id' => $user_id
                ])
                ->where(function ($query) use ($search) {
                    return $query->where('ruc', 'LIKE', "%$search%")
                        ->orWhere('razonsocial', 'LIKE', "%$search%");
                })
                ->orderBy('cliente_auditwholes.created_at', 'DESC');
        } else {
            $customers = ClienteAuditwhole::join('users', 'id', 'user_id')
                ->select('ruc', 'razonsocial', 'name', 'amount')
                ->where('rol', 'LIKE', 'asesor')
                ->where(function ($query) use ($search) {
                    return $query->where('ruc', 'LIKE', "%$search%")
                        ->orWhere('razonsocial', 'LIKE', "%$search%");
                })
                ->orderBy('cliente_auditwholes.created_at', 'DESC');
        }

        return ClienteAuditwholeResources::collection($customers->paginate($paginate));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'ruc' => 'required|unique:cliente_auditwholes',
            'user_id' => 'required|exists:users,id',
            'razonsocial' => 'required',
            'sri' => 'required'
        ]);

        try {
            $custom = ClienteAuditwhole::create($request->all());

            return response()->json(['custom' => $custom]);
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json(['message' => 'KEY_DUPLICATE'], 405);
            }
        }
    }

    public function show($ruc)
    {
        $custom = ClienteAuditwhole::where('ruc', $ruc)->first();

        return response()->json([
            'custom' => $custom,
            'user' =>  User::find($custom->user_id)
        ]);
    }

    public function destroy($ruc)
    {
        ClienteAuditwhole::where('ruc', $ruc)->delete();
    }

    public function update(string $ruc, Request $request)
    {
        try {
            $result = DB::table('cliente_auditwholes')
                ->updateOrInsert(
                    ['ruc' => $ruc],
                    [
                        'razonsocial' => $request->get('razonsocial'),
                        'sri' => $request->get('sri'),
                        'amount' => $request->get('amount'),
                        'user_id' => $request->get('user_id'),
                        'updated_at' => date('Y-m-d H:i:s', strtotime('+5 hours'))
                    ]
                );

            return response()->json(['result' => $result]);
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json(['message' => 'KEY_DUPLICATE'], 405);
            }
        }
    }

    public function updatefichart(string $ruc, Request $request)
    {
        try {
            $result = DB::table('cliente_auditwholes')
                ->updateOrInsert(
                    ['ruc' => $ruc],
                    $request->except('ruc')
                    // [
                    //     'razonsocial' => $request->get('razonsocial'),
                    //     'sri' => $request->get('sri'),
                    //     'updated_at' => date('Y-m-d H:i:s', strtotime('+5 hours'))
                    // ]
                );

            return response()->json(['OK' => 'OK']);
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                return response()->json(['message' => 'KEY_DUPLICATE'], 405);
            }
        }
    }

    public function payments(string $ruc)
    {
        $payments = Payment::where('cliente_auditwhole_ruc', $ruc)
            ->orderBy('year_month', 'DESC')
            ->get();

        return response()->json([
            'payments' => $payments,
            'customer' => ClienteAuditwhole::where('ruc', $ruc)->first()
        ]);
    }
}
