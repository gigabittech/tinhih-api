<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\StoreRequest;
use App\Http\Requests\Invoice\UpdateRequest;
use App\Http\Resources\InvoiceResource;
use App\Models\InvoiceService;
use App\Models\Tax;
use App\Repository\InvoiceRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function __construct(private InvoiceRepository $repository) {}

    /**
     * @OA\Get(
     *     path="v1/invoices",
     *     summary="Get all invoices for the current workspace",
     *     description="Fetches all invoices associated with the currently authenticated user's active workspace.",
     *     tags={"Invoices"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Invoices retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Invoices retrieved successfully"),
     *             @OA\Property(property="invoices", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=101),
     *                 @OA\Property(property="client", type="object", example={"id": 1, "name": "Client A"}),
     *                 @OA\Property(property="biller", type="object", example={"id": 2, "name": "Biller X"}),
     *                 @OA\Property(property="title", type="string", example="Website Development Invoice"),
     *                 @OA\Property(property="serial_number", type="string", example="INV-2024-0001"),
     *                 @OA\Property(property="po_so_number", type="string", example="PO-1001"),
     *                 @OA\Property(property="tax_id", type="integer", example=3),
     *                 @OA\Property(property="issue_date", type="string", format="date", example="2024-04-01"),
     *                 @OA\Property(property="due_date", type="string", format="date", example="2024-04-15"),
     *                 @OA\Property(property="description", type="string", example="Full website development service."),
     *                 @OA\Property(property="subtotal", type="number", format="float", example=1000.00),
     *                 @OA\Property(property="payable_amount", type="number", format="float", example=1150.00),
     *                 @OA\Property(property="is_paid", type="boolean", example=false),
     *                 @OA\Property(property="services", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Frontend Development"),
     *                     @OA\Property(property="quantity", type="integer", example=1),
     *                     @OA\Property(property="price", type="number", format="float", example=500.00),
     *                     @OA\Property(property="total", type="number", format="float", example=500.00)
     *                 )),
     *                 @OA\Property(property="summary", type="object", nullable=true, example={
     *                     "total_services": 2,
     *                     "taxes_applied": 15,
     *                     "grand_total": 1150.00
     *                 })
     *             ))
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error retrieving invoices",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error retrieving invoices")
     *         )
     *     )
     * )
     */

    public function getInvoices(Request $request)
    {
        try {
            $invoices = $this->repository->getWorkspaceInvoices($request->user()->currentWorkspace()->id);
            return response()->json([
                'message' => 'Invoices retrieved successfully',
                'invoices' => InvoiceResource::collection($invoices),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error retrieving invoices',
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="v1/invoices/:id",
     *     summary="Get a specific invoice by ID",
     *     description="Retrieves a single invoice by ID, scoped to the current user's workspace. Includes a tax summary if available.",
     *     tags={"Invoices"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Invoice ID",
     *         @OA\Schema(type="integer", example=101)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Invoice retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Invoice retrieved successfully"),
     *             @OA\Property(property="invoice", type="object",
     *                 @OA\Property(property="id", type="integer", example=101),
     *                 @OA\Property(property="client", type="object", example={"id": 1, "name": "Client A"}),
     *                 @OA\Property(property="biller", type="object", example={"id": 2, "name": "Biller X"}),
     *                 @OA\Property(property="title", type="string", example="Website Development Invoice"),
     *                 @OA\Property(property="serial_number", type="string", example="INV-2024-0001"),
     *                 @OA\Property(property="po_so_number", type="string", example="PO-1001"),
     *                 @OA\Property(property="tax_id", type="integer", example=3),
     *                 @OA\Property(property="issue_date", type="string", format="date", example="2024-04-01"),
     *                 @OA\Property(property="due_date", type="string", format="date", example="2024-04-15"),
     *                 @OA\Property(property="description", type="string", example="Full website development service."),
     *                 @OA\Property(property="subtotal", type="number", format="float", example=1000.00),
     *                 @OA\Property(property="payable_amount", type="number", format="float", example=1150.00),
     *                 @OA\Property(property="is_paid", type="boolean", example=false),
     *                 @OA\Property(property="services", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Frontend Development"),
     *                     @OA\Property(property="quantity", type="integer", example=1),
     *                     @OA\Property(property="price", type="number", format="float", example=500.00),
     *                     @OA\Property(property="total", type="number", format="float", example=500.00)
     *                 )),
     *                 @OA\Property(property="summary", type="object", nullable=true, example={
     *                     "total_services": 2,
     *                     "taxes_applied": 15,
     *                     "grand_total": 1150.00
     *                 })
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error retrieving invoice",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error retrieving invoice")
     *         )
     *     )
     * )
     */


    public function getInvoice(Request $request, $id)
    {
        try {
            $invoice = $this->repository->getWorkspaceInvoice($request->user()->currentWorkspace()->id, $id);
            $invoice['summary'] = $this->repository->generateTaxSummary($invoice);
            return response()->json([
                'message' => 'Invoice retrieved successfully',
                'invoice' => new InvoiceResource($invoice),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error retrieving invoice',
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="v1/invoices",
     *     summary="Create a new invoice",
     *     tags={"Invoices"},
     *     description="Creates a new invoice with services, calculates the subtotal and payable amount including taxes.",
     *     security={{ "bearerAuth":{} }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"client_id", "biller_id", "services"},
     *             @OA\Property(property="client_id", type="integer", example=1),
     *             @OA\Property(property="biller_id", type="integer", example=2),
     *             @OA\Property(property="title", type="string", example="Website Development Invoice"),
     *             @OA\Property(property="serial_number", type="string", example="INV-2024-0001"),
     *             @OA\Property(property="po_so_number", type="string", example="PO-1001"),
     *             @OA\Property(property="tax_id", type="integer", example=3),
     *             @OA\Property(property="issue_date", type="string", format="date", example="2024-04-01"),
     *             @OA\Property(property="due_date", type="string", format="date", example="2024-04-15"),
     *             @OA\Property(property="description", type="string", example="Full website development service."),
     *             @OA\Property(property="services", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="price", type="number", format="float", example=500.00),
     *                 @OA\Property(property="unit", type="integer", example=1),
     *                 @OA\Property(property="date", type="string", format="date", example="2024-04-01"),
     *                 @OA\Property(property="taxes", type="array", @OA\Items(type="integer", example=1)),
     *                 @OA\Property(property="code", type="string", example="WEBDEV-001")
     *             ))
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Invoice created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Invoice created successfully"),
     *             @OA\Property(property="invoice", type="object",
     *                 @OA\Property(property="id", type="integer", example=101),
     *                 @OA\Property(property="client_id", type="integer", example=1),
     *                 @OA\Property(property="biller_id", type="integer", example=2),
     *                 @OA\Property(property="title", type="string", example="Website Development Invoice"),
     *                 @OA\Property(property="serial_number", type="string", example="INV-2024-0001"),
     *                 @OA\Property(property="po_so_number", type="string", example="PO-1001"),
     *                 @OA\Property(property="tax_id", type="integer", example=3),
     *                 @OA\Property(property="issue_date", type="string", format="date", example="2024-04-01"),
     *                 @OA\Property(property="due_date", type="string", format="date", example="2024-04-15"),
     *                 @OA\Property(property="description", type="string", example="Full website development service."),
     *                 @OA\Property(property="subtotal", type="number", format="float", example=1000.00),
     *                 @OA\Property(property="payable_amount", type="number", format="float", example=1150.00),
     *                 @OA\Property(property="is_paid", type="boolean", example=false),
     *                 @OA\Property(property="services", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Frontend Development"),
     *                     @OA\Property(property="quantity", type="integer", example=1),
     *                     @OA\Property(property="price", type="number", format="float", example=500.00),
     *                     @OA\Property(property="total", type="number", format="float", example=500.00)
     *                 )),
     *                 @OA\Property(property="summary", type="object", nullable=true, example={
     *                     "total_services": 2,
     *                     "taxes_applied": 15,
     *                     "grand_total": 1150.00
     *                 })
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error creating invoice",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error creating invoice")
     *         )
     *     )
     * )
     */

    public function createInvoice(StoreRequest $request)
    {
        try {
            DB::beginTransaction();

            // Create invoice
            $invoice = $this->repository->create($request->validated());
            $totalPayable = 0;
            $subtotal = 0;
            $taxSummary = []; // To store summarized taxes


            foreach ($request->services as $service) {
                if (!$service) {
                    continue; // skip if service not found
                }
                // Calculate subtotal for this service
                $lineSubtotal = $service['price'] * $service['unit'];
                // Initialize the payable amount for this service
                $linePayable = $lineSubtotal;


                $totalTaxPercentage = 0;

                if (!empty($service['taxes'])) {
                    $taxModels = Tax::whereIn('id', $service['taxes'])->get();
                    $totalTaxPercentage = $taxModels->sum('percentage');
                }

                $subtotal += $lineSubtotal;
                $linePayableWithTax = $linePayable + ($linePayable * $totalTaxPercentage) / 100;
                $totalPayable += $linePayableWithTax;

                // Store the invoice service
                $invoiceService = InvoiceService::create([
                    'invoice_id' => $invoice->id,
                    'date' => $service['date'] ?? null,
                    'service_id' => $service['id'],
                    'code' => $service['code'] ?? null,
                    'price' => $service['price'],
                    'unit' => $service['unit'] ?? 1,
                    'amount' => $service['price'] * $service['unit'] ?? null,
                    'tax' => $totalTaxPercentage, // Saving tax IDs as comma-separated string
                ]);

                $invoiceService->taxes()->attach($service['taxes']);
            }

            // Update invoice with calculated values
            $invoice->update([
                'subtotal' => $subtotal,
                'payable_amount' => $totalPayable,
                'is_paid' => 0,
            ]);

            DB::commit();

            // Return response
            return response()->json([
                'message' => 'Invoice created successfully',
                'invoice' => new InvoiceResource($invoice),
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error creating invoice',
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="v1/invoices/:id",
     *     summary="Update an existing invoice",
     *     tags={"Invoices"},
     *     description="Updates an existing invoice with new service details and recalculates the subtotal and payable amount.",
     *     security={{ "bearerAuth":{} }},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer", example=101)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"services"},
     *             @OA\Property(property="services", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="price", type="number", format="float", example=500.00),
     *                 @OA\Property(property="unit", type="integer", example=1),
     *                 @OA\Property(property="date", type="string", format="date", example="2024-04-01"),
     *                 @OA\Property(property="taxes", type="array", @OA\Items(type="integer", example=1)),
     *                 @OA\Property(property="code", type="string", example="WEBDEV-001")
     *             )),
     *             @OA\Property(property="is_paid", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Invoice updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Invoice updated successfully"),
     *             @OA\Property(property="invoice", type="object",
     *                 @OA\Property(property="id", type="integer", example=101),
     *                 @OA\Property(property="client_id", type="integer", example=1),
     *                 @OA\Property(property="biller_id", type="integer", example=2),
     *                 @OA\Property(property="title", type="string", example="Website Development Invoice"),
     *                 @OA\Property(property="serial_number", type="string", example="INV-2024-0001"),
     *                 @OA\Property(property="po_so_number", type="string", example="PO-1001"),
     *                 @OA\Property(property="tax_id", type="integer", example=3),
     *                 @OA\Property(property="issue_date", type="string", format="date", example="2024-04-01"),
     *                 @OA\Property(property="due_date", type="string", format="date", example="2024-04-15"),
     *                 @OA\Property(property="description", type="string", example="Full website development service."),
     *                 @OA\Property(property="subtotal", type="number", format="float", example=1000.00),
     *                 @OA\Property(property="payable_amount", type="number", format="float", example=1150.00),
     *                 @OA\Property(property="is_paid", type="boolean", example=false),
     *                 @OA\Property(property="services", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Frontend Development"),
     *                     @OA\Property(property="quantity", type="integer", example=1),
     *                     @OA\Property(property="price", type="number", format="float", example=500.00),
     *                     @OA\Property(property="total", type="number", format="float", example=500.00)
     *                 )),
     *                 @OA\Property(property="summary", type="object", nullable=true, example={
     *                     "total_services": 2,
     *                     "taxes_applied": 15,
     *                     "grand_total": 1150.00
     *                 })
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error updating invoice",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error updating invoice")
     *         )
     *     )
     * )
     */


    public function updateInvoice(UpdateRequest $request, $id)
    {
        try {
            DB::beginTransaction();

            // Update the invoice with the validated request data
            $invoice = $this->repository->update($id, $request->validated());
            if (!$invoice) {
                return response()->json([
                    'message' => 'Invoice not found',
                ], 404);
            }
            $totalPayable = 0;
            $subtotal = 0;

            // Remove existing services if you plan to update them
            $invoice->services()->delete(); // This will remove previous services

            foreach ($request->services as $service) {
                if (!$service) {
                    continue; // skip if service not found
                }

                // Calculate the line subtotal and tax for this service
                $lineSubtotal = $service['price'] * $service['unit'];
                $linePayable = $lineSubtotal;

                // Initialize the total tax percentage for the service
                $totalTaxPercentage = 0;
                if (!empty($service['taxes'])) {
                    // Get the tax models and calculate the total tax percentage
                    $taxModels = Tax::whereIn('id', $service['taxes'])->get();
                    $totalTaxPercentage = $taxModels->sum('percentage');
                }

                $subtotal += $lineSubtotal;

                // Apply the tax to the line total
                $linePayableWithTax = $linePayable + ($linePayable * $totalTaxPercentage) / 100;
                $totalPayable += $linePayableWithTax;

                // Create or update the invoice service
                $invoiceService = InvoiceService::create([
                    'invoice_id' => $invoice->id,
                    'date' => $service['date'] ?? null,
                    'service_id' => $service['id'],
                    'code' => $service['code'] ?? null,
                    'price' => $service['price'],
                    'unit' => $service['unit'] ?? 1,
                    'amount' => $service['price'] * $service['unit'] ?? null,
                    'tax' => $totalTaxPercentage, // Store the total tax percentage
                ]);

                // Attach the selected taxes to the invoice service
                $invoiceService->taxes()->attach($service['taxes']);
            }

            // Update the invoice totals
            $invoice->update([
                'subtotal' => $subtotal,
                'payable_amount' => $totalPayable,
                'is_paid' => $request->has('is_paid') ? $request->is_paid : 0,
            ]);

            DB::commit();

            // Return response with updated invoice data
            return response()->json([
                'message' => 'Invoice updated successfully',
                'invoice' => new InvoiceResource($invoice),
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error updating invoice',
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="v1/invoices/:id",
     *     summary="Delete an invoice",
     *     description="Deletes the specified invoice by its ID. If the invoice doesn't exist, a 404 response will be returned.",
     *     tags={"Invoices"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the invoice to be deleted",
     *         @OA\Schema(type="integer", example=101)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Invoice deleted successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Invoice deleted successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invoice not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Invoice not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error deleting invoice",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error deleting invoice")
     *         )
     *     )
     * )
     */


    public function deleteInvoice(Request $request, $id)
    {
        try {
            $invoice = $this->repository->find($id);
            if (!$invoice) {
                return response()->json([
                    'message' => 'Invoice not found',
                ], 404);
            }
            $this->repository->delete($id);
            return response()->json([
                'message' => 'Invoice deleted successfully',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error deleting invoice',
            ], 500);
        }
    }

    /**
     * @OA\Patch(
     *     path="v1/invoices/:id/mark-as-paid",
     *     summary="Mark an invoice as paid",
     *     description="Marks the specified invoice as paid by updating its `is_paid` field. If the invoice doesn't exist, a 404 response will be returned.",
     *     tags={"Invoices"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of the invoice to mark as paid",
     *         @OA\Schema(type="integer", example=101)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Invoice marked as paid successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Payment successful"),
     *             @OA\Property(property="invoice", type="object",
     *                 @OA\Property(property="id", type="integer", example=101),
     *                 @OA\Property(property="client_id", type="integer", example=1),
     *                 @OA\Property(property="biller_id", type="integer", example=2),
     *                 @OA\Property(property="title", type="string", example="Updated Website Development Invoice"),
     *                 @OA\Property(property="serial_number", type="string", example="INV-2024-0002"),
     *                 @OA\Property(property="po_so_number", type="string", example="PO-1002"),
     *                 @OA\Property(property="tax_id", type="integer", example=3),
     *                 @OA\Property(property="issue_date", type="string", format="date", example="2024-04-10"),
     *                 @OA\Property(property="due_date", type="string", format="date", example="2024-04-25"),
     *                 @OA\Property(property="description", type="string", example="Updated service description"),
     *                 @OA\Property(property="subtotal", type="number", format="float", example=1200.00),
     *                 @OA\Property(property="payable_amount", type="number", format="float", example=1380.00),
     *                 @OA\Property(property="is_paid", type="boolean", example=true),
     *                 @OA\Property(property="services", type="array", @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="Frontend Development"),
     *                     @OA\Property(property="quantity", type="integer", example=2),
     *                     @OA\Property(property="price", type="number", format="float", example=600.00),
     *                     @OA\Property(property="total", type="number", format="float", example=1200.00)
     *                 )),
     *                 @OA\Property(property="summary", type="object", nullable=true, example={
     *                     "total_services": 2,
     *                     "taxes_applied": 15,
     *                     "grand_total": 1380.00
     *                 })
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Invoice not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Invoice not found")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error marking invoice as paid",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Error marking invoice as paid")
     *         )
     *     )
     * )
     */


    public function markAsPaid(Request $request, $id)
    {
        try {
            $invoice = $this->repository->find($id);
            if (!$invoice) {
                return response()->json([
                    'message' => 'Invoice not found',
                ], 404);
            }
            $invoice->update(['is_paid' => 1]);
            return response()->json([
                'message' => 'Payment successfull',
                'invoice' => new InvoiceResource($invoice),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Error marking invoice as paid',
            ], 500);
        }
    }
}
