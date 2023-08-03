<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\CustomPaginator;
use App\Http\Resources\Category\CategoryResource;

class CategoryController extends BaseController
{

    public function index(Request $request) {
        $perPage = $request->query('per_page');
        $category = new CustomPaginator(Category::paginate($perPage));
        return $this->sendResponse($category, 'Cateory retrieved successfully!');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $input['name'] = strtolower($request->name);

        $validator = Validator::make($input, [
            'name' => 'required|unique:categories,name|max:100|string'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $category = Category::create($input);

        return $this->sendResponse(new CategoryResource($category), 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        $input = $request->all();
        $input['name'] = strtolower($request->name);

        if ($input['name'] != $category->name) {
            $validator = Validator::make($input, [
                'name' => 'required|unique:categories,name|max:100|string'
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());
            }
        }

        $category->name = $input['name'];
        $category->save();

        return $this->sendResponse(new CategoryResource($category), 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return $this->sendResponse([], 'Category deleted successfully.');
    }
}
