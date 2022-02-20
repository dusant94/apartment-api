<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Rate;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RateController extends Controller
{



   /**
     * @OA\Post(
     *     path="/rate",
     *     description="Rate apartment",
     *     tags={"Rate"},
     *     @OA\Parameter(
     *          name="rating",
     *          description="Apartment Rating (must be between 0 and 5)",
     *          required=true,
     *          in="query",
     *          @OA\Schema(
     *              type="integer"
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
     *    @OA\Parameter(
     *          name="Accept",
     *          description="application/json",
     *          required=true,
     *          in="header",
     *          example="application/json",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Parameter(
     *          name="Authorization",
     *          description="Token for authorization",
     *          required=true,
     *          in="header",
     *          example="Bearer ...",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *     @OA\Response(response="201", description="Success", @OA\MediaType(mediaType="application/json")),
     *     @OA\Response(response=500,description="Internal server error"),
     *     @OA\Response(response=401,description="Unauthenticated"),
     *     @OA\Response(response=422,description="Unprocessable Entity - validation failed"),
     * )
     */
    public function rate(Request $request){
        try{
            $inputs = $request->validate([
                'rating' => ['required', 'integer', 'between:0,5'],
                'apartment_id' => ['required', 'integer'],
            ]);
            $apartment = Apartment::findOrFail($request['apartment_id']);
            $user = Auth::user();
            $inputs['user_id'] = $user->id;
            $rate = Rate::create($inputs);
            Apartment::updateRating($apartment);
            return response()->json($rate, Response::HTTP_CREATED);
        }catch(Exception $e){
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
