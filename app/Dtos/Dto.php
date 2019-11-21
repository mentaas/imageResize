<?php
/**
 * Created by PhpStorm.
 * User: mento
 * Date: 08.08.2019
 * Time: 9:51 PM
 */

namespace App\Dtos;


class Dto
{
    private $entity;

    public static function make($model)
    {
        return new self($model);
    }

    public function __construct($model)
    {
        $this->entity = (object) $model->toArray();
    }

    public function __get($name)
    {
        return $this->entity->{$name};
    }

}