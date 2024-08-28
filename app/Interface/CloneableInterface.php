<?php

namespace App\Interface;

use Illuminate\Database\Eloquent\Model;

interface CloneableInterface
{
    public function clone(): Model;

    public function setPreservedIdentifier(int $currentChildId): void;

    public function getPreservedIdentifier(): int;
}
