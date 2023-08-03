<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\RoleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\User\UserResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends BaseController
{
    /**
     * Create User
     * @param Request $request
     * @return User
     */
    public function createUser(Request $request)
    {
        try {
            //Validated
            $validator = Validator::make($request->all(),
            [
                'username' => 'required|unique:users,username|min:8|max:255|string',
                'password' => 'required|string|min:8',
                'confirm_password' => 'required|same:password',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'profile' => 'required|image',
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors(), Response::HTTP_BAD_REQUEST);
            }

            // upload avatar for user
            $avatarName = time().'.'.$request->profile->getClientOriginalExtension();
            $request->profile->move(public_path('profile-images'), $avatarName);

            $user = User::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'role_id' => 1,
                'profile' => $avatarName
            ]);

            $success['token'] = $user->createToken("API TOKEN")->plainTextToken;
            $success['user'] = new UserResource($user);
            return $this->sendResponse($success, 'User created successfully', Response::HTTP_CREATED);

        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

     /**
     * Login The User
     * @param Request $request
     * @return User
     */
    public function loginUser(Request $request)
    {
        try {
            $validator = Validator::make($request->all(),
            [
                'username' => 'required',
                'password' => 'required'
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors(), Response::HTTP_UNAUTHORIZED);
            }
            if(!Auth::attempt($request->only(['username', 'password']))){
                return $this->sendError('Username & Password does not match with our record.', [], Response::HTTP_UNAUTHORIZED);
            }

            $user = User::where('username', $request->username)->first();
            $success['token'] = $user->createToken("API TOKEN")->plainTextToken;
            return $this->sendResponse($success, 'User logged in successfully');

        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage(), [], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return $this->sendResponse(null, 'User logged out successfully');
    }

    public function userProfile(Request $request) {
        $user = $request->user();
        $user->roleType;
        return $this->sendResponse($user, 'User retrieve successfully');
    }
}
