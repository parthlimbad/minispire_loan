<?php

namespace App\Repositories;

use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Model;

abstract class Repository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * @var App
     */
    protected $app;

    /**
     * Constructor 
     * 
     * @param  App  $app
     * @author Parth L.
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    /**
     * Make Model instance
     *
     * @throws \Exception
     *
     * @return Model
     * @author Parth L.
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        if (! $model instanceof Model) {
            throw new \Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * Configure the Model
     *
     * @return string'
     * @author Parth L.
     */
    abstract public function model();

    /**
     * get all 
     * 
     * @param array $columns
     * @return mixed
     * @author Parth L.
     */
    public function all($columns = ['*'])
    {
        return $this->model->get($columns);
    }

    /**
     * Create model record
     *
     * @param  array  $input
     * @return Model
     * @author Parth L.
     */
    public function create($input)
    {
        $model = $this->model->newInstance($input);

        $model->save();

        return $model;
    }

    /**
     * Find model record for given id
     *
     * @param  int  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model|null
     * @author Parth L.
     */
    public function find($id, $columns = ['*'])
    {
        $query = $this->model->newQuery();

        return $query->find($id, $columns);
    }

    /**
     * Update model record for given id
     *
     * @param  array  $conditions
     * @return mixed
     * @author Parth L.
     */
    public function findWhere(array $conditions = [])
    {
        return $this->model->where($conditions)->get();
    }

    /**
     * Update model record for given id
     *
     * @param  int  $id
     * @param  array  $input
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Model
     * @author Parth L.
     */
    public function update($id, $input)
    {
        $query = $this->model->newQuery();

        $model = $query->findOrFail($id);

        $model->fill($input);

        $model->save();

        return $model;
    }

    /**
     * Deletes Model
     * 
     * @param  int  $id
     * @return bool|mixed|null
     * @author Parth L.
     */
    public function delete($id)
    {
        $query = $this->model->newQuery();

        $model = $query->findOrFail($id);

        return $model->delete();
    }
}
