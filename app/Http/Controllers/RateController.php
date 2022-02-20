<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Rate;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class RateController extends Controller
{

    public function rate(Request $request){
        try{
            $apartment = Apartment::findOrFail($request['apartment_id']);
            $user = Auth::user();
            $inputs['rating'] = $request['rating'];
            $inputs['user_id'] = $user->id;
            $inputs['apartment_id'] = $apartment->id;
            $rate = Rate::create($inputs);
            Apartment::updateRating($apartment);
            return response()->json($rate, Response::HTTP_CREATED);
        }catch(Exception $e){
            return response()->json($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
