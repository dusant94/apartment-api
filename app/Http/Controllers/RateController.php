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
