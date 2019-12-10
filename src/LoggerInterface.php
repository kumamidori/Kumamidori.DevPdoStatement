<?php
declare(strict_types=1);

namespace Kumamidori\DevPdoStatement;

interface LoggerInterface
{
    /**
     * @param string $query
     * @param string $time
     * @param array  $explain
     * @param array  $warnings
     */
    public function logQuery($query, $time, array $explain, array $warnings);
}
