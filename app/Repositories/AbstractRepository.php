<?php

namespace App\Repositories;

use Exception;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository implements RepositoryInterface
{
    protected $model;

    abstract public function getModelClass(): string;

    public function __construct($targetObject = null)
    {
        if (is_null($targetObject)) {
            $this->model = app()->make($this->getModelClass());
        } else {
            $this->model = $targetObject;
        }
    }

    /**
     *
     */
    public function getById($id)
    {
        return $this->model::findOrFail($id);
    }

    /**
     *
     */
    public function deleteById($id)
    {
        $this->model::findOrFail($id)->delete();
    }

    /**
     *
     */
    public function getAll()
    {
        $model = $this->model::all();
        if ($model) {
            return $model;
        } else {
            return null;
        }
    }

    /**
     *
     */
    public function getBySearch($search_word)
    {
        if (is_null($search_word)) {
            return $this->model::paginate(500);
        }
        $model = $this->model::where($this->model::SEARCH_COLUMN, 'like', "%$search_word%")->paginate(500);
        if ($model) {
            return $model;
        } else {
            return collect();
        }
    }
}
