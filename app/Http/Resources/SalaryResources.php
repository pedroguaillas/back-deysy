<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SalaryResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'month' => $this->month,
            'amount' => $this->amount,
            'cheque' => $this->cheque,
            'amount_cheque' => $this->amount_cheque,
            'balance' => $this->balance,
            'cash' => $this->cash,
            'paid' => $this->paid + $this->paidsoap
        ];
    }
}
