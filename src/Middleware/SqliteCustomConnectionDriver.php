<?php

declare(strict_types=1);

namespace App\Middleware;

use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Driver\Middleware\AbstractDriverMiddleware;

final class SqliteCustomConnectionDriver extends AbstractDriverMiddleware
{
    public function connect(array $params): Connection
    {
        $conn = parent::connect($params);
        $conn->exec('PRAGMA foreign_keys = ON;');
        return $conn;
    }
}
