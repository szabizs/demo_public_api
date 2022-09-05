<?php

namespace App\Repositories;

use App\Contracts\BrandContract;
use App\Models\Brand;
use App\Traits\UploadableFile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\UploadedFile;
use InvalidArgumentException;

class BrandRepository extends BaseRepository implements BrandContract
{

    use UploadableFile;

    public function __construct(Brand $brand)
    {
        parent::__construct($brand);
        $this->brand = $brand;
    }

	public function filterFor(array $params)
	{
		// TODO: Implement filterFor() method.
	}

	public function listBrands(string $order = 'id', string $sort = 'desc', array $columns = ['*'])
	{
		// TODO: Implement listBrands() method.
	}

	public function findBrandById(int $id)
	{
        try {
            return $this->findOneOrFail($id);

        } catch (ModelNotFoundException $e) {

            throw new ModelNotFoundException($e);
        }
	}

	public function createBrand(array $params)
	{
        try {
            $collection = collect($params);

            $logo = null;

            if ($collection->has('logo') && ($collection->get('logo') instanceof UploadedFile)) {
                $logo = $this->uploadOne($params['logo'], 'images/brands');
            }

            $merge = $collection->merge(compact('logo'));

            $brand = new Brand($merge->all());

            $brand->save();

            return $brand;

        } catch (QueryException $exception) {
            throw new InvalidArgumentException($exception->getMessage());
        } catch (\Exception $e) {
            \Log::debug($e->getMessage());
        }
	}

	public function updateBrand(int $id, array $params)
	{
        $brand = $this->findBrandById($id);

        $collection = collect($params)->except('_token');
        $logo = null;

        if($collection->has('logo') && ($collection->get('logo') instanceof UploadedFile)) {
            if (!is_null($brand->logo)) {
                $this->deleteOne($brand->logo);
            }
            $logo = $this->uploadOne($params['logo'], 'images/brands');
        }

        if(!is_null($logo)) {
            $merge = $collection->merge(compact('logo'));
        }

        if(is_null($logo)) {
            $merge = $collection;
        }

        $brand->update($merge->all());

        return $brand;
	}

	public function deleteBrand($id)
	{
		$brand = $this->findBrandById($id);

        $brand->delete();

        return $brand;
	}

    public function deleteLogo(Brand $brand): bool
    {

        if(\Storage::disk('public')->delete($brand->storageLogo)) {
            $brand->logo = null;
            $brand->save();

            return true;
        }

        return false;
    }
}
