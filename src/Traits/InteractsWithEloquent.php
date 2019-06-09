<?php

namespace Ivan770\HttpClient\Traits;

use Illuminate\Database\Eloquent\Model;
use Ivan770\HttpClient\Exceptions\ClassIsNotModel;
use Ivan770\HttpClient\Exceptions\EloquentNotAvailable;

trait InteractsWithEloquent
{
    protected $model;

    protected function eloquentAvailable()
    {
        if (class_exists(Model::class)) {
            return true;
        }
        throw new EloquentNotAvailable("Eloquent model class not found");
    }

    protected function isModel($class)
    {
        if ($class instanceof Model) {
            return true;
        }
        throw new ClassIsNotModel("Provided class is not suitable for interaction");
    }

    public function setModel($model)
    {
        if ($this->eloquentAvailable() && $this->isModel($model)) {
            $this->model = $model;
        }
        return $this;
    }

    public function fetchModel($model = null)
    {
        if (!is_null($model)) {
            $this->setModel($model);
        }
        if ($this->isModel($this->model)) {
            $this->applyRequestOptions(["json" => $this->model->toArray()]);
            return $this;
        }
    }
}