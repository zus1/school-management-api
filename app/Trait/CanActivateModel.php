<?php

namespace App\Trait;

use App\Interface\CanBeActiveInterface;

trait CanActivateModel
{
    public function toggleActive(CanBeActiveInterface $model, bool $active): CanBeActiveInterface
    {
        $model->setActive($active);

        $model->save();

        return $model;
    }
}
