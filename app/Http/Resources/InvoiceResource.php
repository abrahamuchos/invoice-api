<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int                             $id
 * @property int                             $customer_id
 * @property int                             $invoice_number
 * @property string                          $uuid
 * @property float                           $amount
 * @property string                          $status
 * @property string                          $billed_dated
 * @property string|null                     $paid_dated
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer|null  $customer
 */
class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customerId' => $this->customer_id,
            'invoiceNumber' => $this->invoice_number,
            'uuid' => $this->uuid,
            'amount' => $this->amount,
            'status' => $this->status,
            'billedDated' => $this->billed_dated,
            'paidDated' => $this->paid_dated,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'customer' => new CustomerResource($this->whenLoaded('customer'))
        ];
    }
}
