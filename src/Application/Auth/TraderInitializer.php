<?php

declare(strict_types=1);

namespace App\Application\Auth;

use App\Entity\Market\Trader;

/*
* Creates a new Trader for e.g. a newly registered user
*/
interface TraderInitializer
{
    public function init(): Trader;
}
