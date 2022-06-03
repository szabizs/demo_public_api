<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

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
        return Inertia::render('Admin/Categories', [
            'categoryCount' => Category::query()->count(),
            'categories' => Category::where('parent_id',0)->with(['childrenRecursive'])->get(),
        ]);
    }

    /**
     * @param  Category  $category
     *
     * @return mixed
     */
    public function show(Category $category)
    {
        try {
//                return Inertia::share('categoryData', $categoryData);
                return response()->api(
                    is_success: true,
                    message: 'Results for the current category',
                    description: $this->description,
                    data: [
                        'category' => $category
                    ],
                    code: 200
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
    public function update(Category $category)
    {
        if($category->update(request()->all())) {
            return Redirect::route('admin.categories.index');
        }
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
