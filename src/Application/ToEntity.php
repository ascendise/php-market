<?php

declare(strict_types=1);

namespace App\Application;

interface ToEntity
{
    /**
    * Method to map DTO/Model to a domain entity
    *
    * @template T
    * @return T
    */
    public function toEntity(): mixed;
}
