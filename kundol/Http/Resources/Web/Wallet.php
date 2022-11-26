<?php

namespace App\Http\Resources\Web;

use Illuminate\Http\Resources\Json\JsonResource;

class Wallet extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'transaction_description' => $this->transaction->description,
            'account_id' => $this->account_id,
            'reference_id' => $this->reference_id,
            'user_id' => $this->user_id,
            'type' => $this->type,
            'warehouse' => $this->warehouse_id,
            'debit_amount' => $this->dr_amount,
            'credit_amount' => $this->cr_amount,
            'description' => $this->description,
            'date' => $this->created_at->format('d/M/Y'),
            'total_debit' => $this->SumOfDebit(),
            'total_credit' => $this->SumOfCredit()
        ];
    }
}
