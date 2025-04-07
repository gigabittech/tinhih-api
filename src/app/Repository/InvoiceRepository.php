<?php

namespace App\Repository;

use App\Models\Invoice;
use App\Models\Tax;
use App\Repository\Implementation\BaseRepository;
use Illuminate\Support\Facades\Log;

class InvoiceRepository extends BaseRepository

{
    /**
     * Create a new class instance.
     */
    public function __construct(Invoice $model)
    {
        parent::__construct($model);
    }

    public function getWorkspaceInvoices($workspaceId)
    {
        return $this->model->byWorkspace($workspaceId)->get();
    }

    public function getWorkspaceInvoice($workspaceId, $invoiceId)
    {
        return $this->model->byWorkspace($workspaceId)->findOrFail($invoiceId);
    }



    public function generateTaxSummary($invoice)
    {
        $summary = [];

        $invoiceServices = $invoice->services()->with('taxes')->get();

        foreach ($invoiceServices as $service) {
            $lineSubtotal = $service->price * $service->unit;

            foreach ($service->taxes as $tax) {
                $taxKey = $tax->id;
                // If this tax is not yet added to summary, initialize it
                if (!isset($summary[$taxKey])) {
                    $summary[$taxKey] = [
                        'id' => $tax->id,
                        'name' => $tax->name,
                        'percentage' => $tax->percentage,
                        'amount_on' => 0,
                        'tax_amount' => 0,
                    ];
                }


                // Add this service's subtotal to the total for this tax
                $summary[$taxKey]['amount_on'] += $lineSubtotal;
            }
        }

        // Now calculate the tax amount based on the accumulated 'amount_on'
        foreach ($summary as &$taxSummary) {
            $taxSummary['tax_amount'] = ($taxSummary['amount_on'] * $taxSummary['percentage']) / 100;
        }

        return array_values($summary); // Return as indexed array
    }
}
