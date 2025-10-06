<?php

declare(strict_types=1);

namespace App\Middleware;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsMiddleware;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Driver\Middleware;

#[AsMiddleware]
final class SqliteCustomConnectionMiddleware implements Middleware
{
    public function wrap(Driver $driver): Driver
    {
        return new SqliteCustomConnectionDriver($driver);
    }
}
