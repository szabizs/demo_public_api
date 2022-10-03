<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Product;
use App\Traits\UploadableFile;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryRepository extends BaseRepository implements \App\Contracts\CategoryContract
{

    use UploadableFile;

    public function __construct(Category $category)
    {
        parent::__construct($category);
        $this->model = $category; // This is the model that we are binding to

    }

    public function filterFor(array $params)
    {
        // TODO: Implement filterFor() method.
    }

    public function listCategories(string $order = 'id', string $sort = 'desc', array $columns = ['*'])
    {
        return $this->all($columns, $order, $sort);
    }

    public function findCategoryById(int $id)
    {
        // TODO: Implement findCategoryById() method.
    }

    public function createCategory(array $params)
    {
        // TODO: Implement createCategory() method.
    }

    public function updateCategory(array $params)
    {
        // TODO: Implement updateCategory() method.
    }

    public function deleteCategory($id)
    {
        // TODO: Implement deleteCategory() method.
    }
}
