<?php

namespace App\Http\Controllers;

use App\Filters\AuthRelationFilters;
use Zus1\LaravelBaseRepository\Controllers\BaseCollectionController;

class CustomBaseCollectionController extends BaseCollectionController
{
    protected AuthRelationFilters $authRelationFilters;

    public function customAddCollectionRelation(array $relation): void
    {
        $this->addCollectionRelation($relation);
    }

    public function setAuthRelationshipFilters(AuthRelationFilters $filters): void
    {
        $this->authRelationFilters = $filters;
    }
}
