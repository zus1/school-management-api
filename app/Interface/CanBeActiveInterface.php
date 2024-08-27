<?php

namespace App\Interface;

interface CanBeActiveInterface
{
    public function setActive(bool $active): void;
}
