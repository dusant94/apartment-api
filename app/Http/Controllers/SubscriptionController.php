<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{

   /**
     * @OA\Post(
     *     path="/subscribe",
     *     description="Subscribe to apartment price",
     *     tags={"Subscription"},
     *     @OA\Parameter(
     *          name="price_limit",
     *          description="Price limit for sending notification",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="apartment_id",
     *          description="ID of apartment ",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\Response(response="201", description="Success", @OA\MediaType(mediaType="application/json")),
     *     @OA\Response(response=500,description="Internal server error"),
     *     @OA\Response(response=401,description="Unauthenticated"),
     *     @OA\Response(response=422,description="Unprocessable Entity - validation failed"),
     * )
     */
    public function subscribe(Request $request)
    {
        try {
            $inputs = $request->validate([
                'apartment_id' => ['required', 'integer'],
                'price_limit' => ['required', 'numeric'],
            ]);
            $user = Auth::user();
            $inputs['user_id'] = $user->id;
            $subsription = Subscription::create($inputs);
            return response()->json($subsription, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
