<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\User\UserResource;
use App\Repository\UserRepository;
use App\Traits\AuthAvatarTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API Documentation",
 *      description="API documentation for your Laravel application",
 *      @OA\Contact(
 *          email="support@example.com"
 *      ),
 * )
 *
 * @OA\PathItem(path="/")
 */
class AuthController extends Controller
{
    use AuthAvatarTrait;
    public function __construct(protected UserRepository $repository) {}

    /**
     * @OA\Post(
     *     path="/api/v1/auth/login",
     *     summary="Login user",
     *     tags={"Auth"},
     *     description="This endpoint allows a user to log in using their email and password. A valid token is returned upon successful authentication.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="nayem@gigabit.agency"),
     *             @OA\Property(property="password", type="string", format="password", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logged in successfully"),
     *             @OA\Property(property="token", type="string", example="your-generated-token")
     *         ),
     *         @OA\Examples(
     *             example="successResponse",
     *             summary="Example response for a successful login",
     *             value={
     *                 "message": "Logged in successfully",
     *                 "token": "your-generated-token"
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Invalid email or password"),
     *             @OA\Property(property="success", type="boolean", example=false)
     *         ),
     *         @OA\Examples(
     *             example="unauthorizedResponse",
     *             summary="Example response for invalid credentials",
     *             value={
     *                 "message": "Invalid email or password",
     *                 "success": false
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred."),
     *             @OA\Property(property="success", type="boolean", example=false)
     *         ),
     *         @OA\Examples(
     *             example="serverErrorResponse",
     *             summary="Example response for internal server error",
     *             value={
     *                 "message": "An unexpected error occurred.",
     *                 "success": false
     *             }
     *         )
     *     )
     * )
     */

    public function login(LoginRequest $request)
    {
        try {
            $data = $request->validated();
            $user = $this->repository->findByEmail($data['email']);

            if (!$user || !Hash::check($data['password'], $user->password)) {
                return response()->json([
                    'message' => 'Incorrect credentials are given'
                ], 404);
            }

            $token = $user->createToken($user->name)->plainTextToken;

            return response()->json([
                'message' => "Logged in successfull",
                'payload' => ["token" => $token]
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false,
            ], 500);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/auth/register",
     *     summary="Register a new user",
     *     tags={"Auth"},
     *     description="This endpoint allows a user to register by providing their name, email, password, and other required details.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(property="password_confirmation", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Account created successfully"),
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="payload",
     *                 type="object",
     *                 @OA\Property(property="token", type="string", example="your-generated-token"),
     *                 @OA\Property(property="user", type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="email", type="string", example="johndoe@example.com")
     *                 )
     *             )
     *         ),
     *         @OA\Examples(
     *             example="successResponse",
     *             summary="Example response for a successful registration",
     *             value={
     *                 "message": "Account created successfully",
     *                 "success": true,
     *                 "payload": {
     *                     "token": "your-generated-token",
     *                     "user": {
     *                         "id": 1,
     *                         "name": "John Doe",
     *                         "email": "johndoe@example.com"
     *                     }
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The email field is required."),
     *             @OA\Property(property="errors", type="object",
     *                 @OA\Property(property="email", type="array",
     *                     @OA\Items(type="string", example="The email field is required.")
     *                 )
     *             )
     *         ),
     *         @OA\Examples(
     *             example="validationErrorResponse",
     *             summary="Example response for validation error",
     *             value={
     *                 "message": "The email field is required.",
     *                 "errors": {
     *                     "email": {
     *                         "The email field is required."
     *                     }
     *                 }
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred."),
     *             @OA\Property(property="success", type="boolean", example=false)
     *         ),
     *         @OA\Examples(
     *             example="serverErrorResponse",
     *             summary="Example response for internal server error",
     *             value={
     *                 "message": "An unexpected error occurred.",
     *                 "success": false
     *             }
     *         )
     *     )
     * )
     */

    public function register(RegisterRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = $request->validated();
            $data['avatar'] = $this->avatar($data['name']);

            $user = new UserResource($this->repository->create($data));
            $token = $user->createToken($user->name)->plainTextToken;

            DB::commit();
            return response()->json([
                'message' => "Account created successfully",
                'success' => true,
                'payload' => ["token" => $token, "user" => $user]
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false,
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/auth/logout",
     *     summary="Logout user",
     *     tags={"Auth"},
     *     security={{ "bearerAuth":{} }},
     *     description="This endpoint allows an authenticated user to log out by revoking their issued tokens.",
     *     @OA\Response(
     *         response=200,
     *         description="User logged out successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User logged out successfully"),
     *             @OA\Property(property="success", type="boolean", example=true)
     *         ),
     *         @OA\Examples(
     *             example="successResponse",
     *             summary="Example response for successful logout",
     *             value={
     *                 "message": "User logged out successfully",
     *                 "success": true
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - No valid token provided",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthorized - Token not provided or invalid"),
     *             @OA\Property(property="success", type="boolean", example=false)
     *         ),
     *         @OA\Examples(
     *             example="unauthorizedResponse",
     *             summary="Example response for unauthorized access",
     *             value={
     *                 "message": "Unauthorized - Token not provided or invalid",
     *                 "success": false
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="An unexpected error occurred."),
     *             @OA\Property(property="success", type="boolean", example=false)
     *         ),
     *         @OA\Examples(
     *             example="serverErrorResponse",
     *             summary="Example response for internal server error",
     *             value={
     *                 "message": "An unexpected error occurred.",
     *                 "success": false
     *             }
     *         )
     *     )
     * )
     */

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return response()->json([
                'message' => "User logged out",
                'success' => true,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false,
            ], 500);
        }
    }


    /**
     * @OA\Get(
     *     path="/api/v1/user",
     *     summary="Get User Details",
     *     security={{ "bearerAuth":{} }},
     *     description="Fetches details of the currently authenticated user.",
     *     tags={"User"},
     *     @OA\Response(
     *         response=200,
     *         description="User details retrieved successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="User details"
     *             ),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="role",
     *                     type="string",
     *                     example="client"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     example="Developr"
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     example="nayem@gigabit.agency"
     *                 ),
     *                 @OA\Property(
     *                     property="avatar",
     *                     type="string",
     *                     format="uri",
     *                     example="https://ui-avatars.com/api/?name=Developr"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized - User not authenticated"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */

    public function getUser(Request $request)
    {
        return response()->json([
            'message' => "User details",
            'user' => new UserResource($request->user())
        ], 200);
    }
}
