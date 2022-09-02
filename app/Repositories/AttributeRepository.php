<?php

namespace App\Repositories;

use App\Contracts\AttributeContract;
use App\Models\Attribute;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class AttributeRepository extends BaseRepository implements AttributeContract
{

    public function __construct(Attribute $model)
	{
        parent::__construct($model);
        $this->model = $model;
	}

	public function listAttributes(string $order = 'id', string $sort = 'desc', array $columns = ['*'])
	{
		return $this->all($columns, $order, $sort);
	}

	public function findAttributeById(int $id)
	{
        try {
            return $this->findOneOrFail($id);

        } catch (ModelNotFoundException $e) {

            throw new ModelNotFoundException($e);
        }
	}

	public function createAttribute(array $params)
	{
        try {
            $collection = collect($params);

            $attribute = new Attribute($collection->all());

            $attribute->save();

            return $attribute;

        } catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        }
	}

	public function updateAttribute(int $id, array $params)
	{
        $attribute = $this->findAttributeById($id);

        $collection = collect($params)->except('_token');

        $attribute->update($collection->all());

        return $attribute;
	}

	public function deleteAttribute($id)
	{
        $attribute = $this->findAttributeById($id);

        $attribute->delete();

        return $attribute;
	}

    public function filterFor(array $params)
    {
        return $this->filter($params);
    }

    public function orderByName()
    {
        return $this->orderByNameImp();
    }
}
