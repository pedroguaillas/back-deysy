<?php

namespace App\Http\Controllers;

use App\Models\ClienteAuditwhole;
use Illuminate\Http\Request;
use App\Http\Resources\PaymentResources;
use App\Models\Payment;
use App\Models\SalaryAdvanceOfPay;

class PaymentController extends Controller
{
    public function paymentlist(Request $request)
    {
        $ruc = null;
        $paginate = 15;

        if ($request) {
            $ruc = $request->ruc;
            $paginate = $request->has('paginate') ? $request->paginate : $paginate;
        }

        $payments = Payment::where('cliente_auditwhole_ruc', $ruc)
            ->orderBy('year_month', 'DESC');

        return response()->json([
            'payments' => PaymentResources::collection($payments->paginate($paginate)),
            'customer' => ClienteAuditwhole::where('ruc', $ruc)->first()
        ]);
    }

    public function store(Request $request)
    {
        $payment = Payment::where([
            'cliente_auditwhole_ruc' => $request->cliente_auditwhole_ruc,
            'year_month' => $request->year_month,
            // 'date' => $request->date
        ])->get();

        if (count($payment) > 0) {
            return response()->json(['msm' => 'Ya existe pago de ese mes'], 405);
        }

        $payment = Payment::create($request->all());

        return response()->json(['payment' => $payment]);
    }

    // Pagos de un cliente por con cruce
    public function paymentcross(string $ruc)
    {
        $payments = Payment::where([
            'cliente_auditwhole_ruc' => $ruc,
            'type' => 'Cruce'
        ])
            ->whereNotIn('id', SalaryAdvanceOfPay::select('payment_id')->get())
            ->get();
        // $payments = Payment::where([
        //     'cliente_auditwhole_ruc' => $ruc,
        //     'type' => 'Cruce'
        // ])->get();

        return response()->json(['payments' => $payments]);
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::find($id);
        $payment->update($request->all());

        return response()->json(['payment' => $payment]);
    }

    public function destroy($id)
    {
        $payment = Payment::find($id);
        $payment->delete();
    }
}
