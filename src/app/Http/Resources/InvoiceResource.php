<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge([
            'id' => $this->id,
            'client' => $this->client,
            'biller' => $this->biller,
            'title' => $this->title,
            'serial_number' => $this->serial_number,
            'po_so_number' => $this->po_so_number,
            'tax_id' => $this->tax_id,
            'issue_date' => $this->issue_date,
            'due_date' => $this->due_date,
            'description' => $this->description,
            'subtotal' => $this->subtotal,
            'payable_amount' => $this->payable_amount,
            'is_paid' => $this->is_paid,
            'services' => InvoiceServiceResource::collection($this->services),
        ], $this->summary ? ['summary' => $this->summary] : []);
    }
}
