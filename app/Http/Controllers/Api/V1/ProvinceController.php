<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Province;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\CustomPaginator;
use App\Http\Resources\Province\ProvinceResource;

class ProvinceController extends BaseController
{

    public function index(Request $request) {
        $perPage = $request->query('per_page');
        $province = new CustomPaginator(Province::paginate($perPage));
        return $this->sendResponse($province, 'Province retrieved successfully!');
    }

    public function show(Province $province)
    {
        return $this->sendResponse(new ProvinceResource($province), 'Province detail retrieved successfully.');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $input['name'] = strtolower($request->name);

        $validator = Validator::make($input, [
            'name' => 'required|unique:provinces,name|max:100|string'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $province = Province::create($input);

        return $this->sendResponse(new ProvinceResource($province), 'Province created successfully.');
    }

    public function update(Request $request, Province $province)
    {
        $input = $request->all();
        $input['name'] = strtolower($request->name);

        if ($input['name'] != $province->name) {
            $validator = Validator::make($input, [
                'name' => 'required|unique:provinces,name|max:100|string'
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());
            }
        }

        $province->name = $input['name'];
        $province->save();

        return $this->sendResponse(new ProvinceResource($province), 'Province updated successfully.');
    }

    public function destroy(Province $province)
    {
        $province->delete();

        return $this->sendResponse([], 'Province deleted successfully.');
    }
}
