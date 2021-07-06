<?php

namespace App\Http\Controllers\API;


use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;
use App\UserAccount;
use Validator;
use Hash;
use Str;

use App\Jobs\UserNotification;

class AuthController extends BaseController
{
    use AuthenticatesUsers;

    protected $maxAttempts = 5;
    protected $lockoutTime = 300;

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError(['Validation Error.' => $validator->errors()], 401);
        }


        if (UserAccount::where('email', $request['email'])->exists()) {
            return $this->sendError('Registration Error', ['Error' => 'Email already taken.'], 400);
        } else {
            $input = $request->all();
            $input['password'] = Hash::make($input['password']);
            $user = new UserAccount;
            $user->email = $input['email'];
            $user->password = $input['password'];
            $user->save();

            $success['token'] =  $user->createToken('MyApp')->accessToken;
            $success['email'] =  $user->email;

            $data['email'] = $user->email;

            dispatch(new UserNotification($data));

            return $this->sendResponse($success, 'User successfully registered.', 201);
        }
    }

    public function login(Request $request)
    {
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendError('Authentication Error', ['Error' => 'too many login attempts!'], 401);
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = request()->except(['_token']);
       
        if (!auth()->attempt([
            'email' => $input['email'],
            'password' => $input['password'],
        ])) {
            $this->incrementLoginAttempts($request);
            return $this->sendError('Authentication Error', ['Error' => 'Invalid credentials!'], 401);
        }

        $user = auth()->user();
        $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['email'] = $user->email;

        $this->clearLoginAttempts($request);

        return $this->sendResponse($success, 'Login successfully.', 201);
    }
}
