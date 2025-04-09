<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tax\StoreRequest;
use App\Http\Requests\Tax\UpdateRequest;
use App\Http\Resources\TaxResource;
use App\Repository\TaxRepository;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function __construct(private TaxRepository $repository) {}

    /**
     * @OA\Get(
     *     path="/taxes",
     *     summary="Get all taxes",
     *     tags={"Taxes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Taxes retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Taxes retrieved successfully"),
     *             @OA\Property(property="taxes", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="VAT"),
     *                 @OA\Property(property="percentage", type="number", format="float", example=15.0),
     *                 @OA\Property(property="is_active", type="boolean", example=true)
     *             ))
     *         )
     *     )
     * )
     */

    public function getTaxes(Request $request)
    {
        $taxes = $this->repository->getWorkspaceTaxes($request->user()->currentWorkspace()->id);
        return response()->json([
            'message' => 'Taxes retrieved successfully',
            'taxes' => TaxResource::collection($taxes),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/taxes/:id",
     *     summary="Get a specific tax by ID",
     *     tags={"Taxes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tax retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Tax retrieved successfully"),
     *             @OA\Property(property="tax", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="VAT"),
     *                 @OA\Property(property="percentage", type="number", format="float", example=15.0),
     *                 @OA\Property(property="is_active", type="boolean", example=true)
     *             )
     *         )
     *     )
     * )
     */

    public function getTax(Request $request, $id)
    {
        try {
            $tax = $this->repository->getWorkspaceTax($request->user()->currentWorkspace()->id, $id);
            return response()->json([
                'message' => 'Tax retrieved successfully',
                'tax' => new TaxResource($tax),
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Tax not found',
            ], 404);
        }
    }

    /**
     * @OA\Post(
     *     path="/taxes",
     *     summary="Create a new tax",
     *     tags={"Taxes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "percentage"},
     *             @OA\Property(property="name", type="string", example="Service Tax"),
     *             @OA\Property(property="percentage", type="number", format="float", example=5.0),
     *             @OA\Property(property="is_active", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Tax created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tax created successfully"),
     *             @OA\Property(property="tax", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Service Tax"),
     *                 @OA\Property(property="percentage", type="number", format="float", example=5.0),
     *                 @OA\Property(property="is_active", type="boolean", example=true)
     *             )
     *         )
     *     )
     * )
     */


    public function createTax(StoreRequest $request)
    {
        try {
            $tax = $this->repository->create($request->validated());
            return response()->json([
                'message' => 'Tax created successfully',
                'tax' => new TaxResource($tax),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating tax',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/taxes/:id",
     *     summary="Update an existing tax",
     *     tags={"Taxes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Tax Name"),
     *             @OA\Property(property="rate", type="number", format="float", example=10.0),
     *             @OA\Property(property="type", type="string", example="fixed"),
     *             @OA\Property(property="is_active", type="boolean", example=false)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tax updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tax updated successfully"),
     *             @OA\Property(property="tax", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Updated Tax Name"),
     *                 @OA\Property(property="percentage", type="number", format="float", example=10.0),
     *                 @OA\Property(property="is_active", type="boolean", example=false)
     *             )
     *         )
     *     )
     * )
     */

    public function updateTax(UpdateRequest $request, $id)
    {
        $tax = $this->repository->update($id, $request->validated());
        return response()->json([
            'message' => 'Tax updated successfully',
            'tax' => new TaxResource($tax),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/taxes/:id",
     *     summary="Delete a tax",
     *     tags={"Taxes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tax deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Tax deleted successfully")
     *         )
     *     )
     * )
     */

    public function deleteTax(Request $request, $id)
    {
        $this->repository->delete($id);
        return response()->json([
            'message' => 'Tax deleted successfully',
        ]);
    }
}
