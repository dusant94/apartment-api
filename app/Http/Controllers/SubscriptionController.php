<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        try {
            $inputs = $request->validate([
                'apartment_id' => ['required', 'string'],
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
