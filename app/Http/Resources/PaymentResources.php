<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResources extends JsonResource
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
            'atts' => [
                'year_month' => $this->year_month,
                'note' => $this->note,
                'amount' => $this->amount,
                'type' => $this->type,
                'voucher' => $this->voucher,
                'date' => $this->date,
            ]
        ];
    }
}
