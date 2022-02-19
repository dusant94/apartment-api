<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Str;

class RegisterController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function getToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['name'] = Str::random(10);
        $input['email_verified_at'] = now();
        $input['remember_token'] = Str::random(10);

        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;

        return $this->sendResponse($success, 'User register successfully.');
    }
}
