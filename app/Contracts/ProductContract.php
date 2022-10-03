<?php

namespace App\Contracts;

interface ProductContract
{
    public function filterFor(array $params);

    public function listProducts(string $order = 'id', string $sort = 'desc', array $columns = ['*']);

    public function findProductById(int $id);

    public function createProduct(array $params);

    public function updateProduct(int $id, array $params);

    public function deleteProduct($id);
}
