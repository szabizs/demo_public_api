<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryResourceController extends Controller
{

    private string $description = 'This endpoint retuns the categories and its recursive children.';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
    }

    /**
     * @param  Category  $category
     *
     * @return mixed
     */
    public function show(Category $category)
    {
        try {
            $categoryData = Category::with(['childrenRecursive'])
                ->where('parent_id', $category->id)
                ->select(['id', 'parent_id', 'name', 'slug'])
                ->get();
            if($categoryData->count() > 0) {
                return response()->api(
                    is_success: true,
                    message: 'Results for the current category',
                    description: $this->description,
                    data: [
                        'category' => [
                            $categoryData
                        ]
                    ]
                );
            }


            return response()->api(
                is_success: false,
                message: 'Category could not be found',
                description: $this->description,
            );
        } catch (\Exception $e) {
            return response()->api(
                is_success: false,
                message: 'Error retrieving category.',
                description: $this->description,
            );
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
