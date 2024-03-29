<?php
declare(strict_types=1);

namespace Kumamidori\DevPdoStatement;

class Logger implements LoggerInterface
{
    /**
     * EXPLAIN
     *
     * @var array
     */
    public $explain;

    /**
     * SHOW WARNINGS
     *
     * @var array
     */
    public $warnings;

    /**
     * {@inheritdoc}
     */
    public function logQuery($query, $time, array $explain, array $warnings)
    {
        $log = sprintf('time:%s query: %s', $time, $query);
        error_log($log);
        $this->explain = $explain;
        $this->warnings = $warnings;
        if ($warnings) {
            error_log('warnings:' . (string) json_encode($warnings, JSON_PRETTY_PRINT));
            error_log('explain :' . (string) json_encode($explain, JSON_PRETTY_PRINT));
        }
    }
}
