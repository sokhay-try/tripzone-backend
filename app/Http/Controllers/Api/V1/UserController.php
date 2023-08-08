<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\CustomPaginator;
use Symfony\Component\HttpFoundation\Response;

class UserController extends BaseController
{
    public function index(Request $request) {
        $perPage = $request->query('per_page');
        $user = new CustomPaginator(User::with('roleType')->paginate($perPage));
        return $this->sendResponse($user, 'User retrieved successfully!');
    }

    public function show(User $user)
    {
        $user->roleType;
        return $this->sendResponse(new UserResource($user), 'User detail retrieved successfully.');
    }

    public function update(Request $request, User $user)
    {
        // will do next time
    }

    public function updateUserStatusToActive(Request $request)
    {
        return $this->updateUserStatus($request, 'active');
    }

    public function updateUserStatusToInactive(Request $request)
    {
        return $this->updateUserStatus($request, 'inactive');
    }

    public function updateUserStatus(Request $request, $status)
    {
        $validator = Validator::make($request->all(),
        [
            'id' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(), Response::HTTP_BAD_REQUEST);
        }
        $user = User::findOrFail($request->id);
        $user->status = $status;
        $user->save();

        return $this->sendResponse(new UserResource($user), 'User status updated successfully.');
    }
}
