<?php
declare(strict_types=1);

namespace Kumamidori\DevPdoStatement;

final class DevPdoStatement extends \PdoStatement
{
    /**
     * Bound parameters
     *
     * @var array
     */
    private $params = [];

    /**
     * Interpolate Query
     *
     * @var string
     */
    public $interpolateQuery;

    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var QueryInterpolater
     */
    private $queryInterpolater;

    protected function __construct(\PDO $db, LoggerInterface $logger)
    {
        $this->pdo = $db;
        $this->logger = $logger;
        $this->queryInterpolater = new QueryInterpolater();
    }

    /**
     * {@inheritdoc}
     */
    public function bindValue($parameter, $value, $dataType = \PDO::PARAM_STR)
    {
        $this->params[$parameter] = $value;
        parent::bindValue($parameter, $value, $dataType);
    }

    /**
     * {@inheritdoc}
     */
    public function bindParam($paramno, &$param, $dataType = \PDO::PARAM_STR, $length = null, $driverOptions = null)
    {
        $this->params[$paramno] = &$param;
        parent::bindParam($paramno, $param, $dataType, (int) $length, $driverOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function execute($bountInputParameters = null)
    {
        $start = microtime(true);
        parent::execute($bountInputParameters);
        $time = microtime(true) - $start;
        $this->interpolateQuery = $this->queryInterpolater->interpolate($this->queryString, $this->params);
        list($explain, $warnings) = $this->getExplain($this->interpolateQuery);
        $this->logger->logQuery($this->interpolateQuery, $time, $explain, $warnings);
    }

    /**
     * @param string $interpolateQuery
     *
     * @return array
     */
    private function getExplain($interpolateQuery)
    {
        $explainSql = sprintf('EXPLAIN %s', $interpolateQuery);
        try {
            $sth = $this->pdo->query($explainSql);
            $explain = $sth->fetchAll(\PDO::FETCH_ASSOC);
            $sth = $this->pdo->query('SHOW WARNINGS');
            $sth instanceof \PDOStatement ? $warnings = $sth->fetchAll(\PDO::FETCH_ASSOC) : $warnings = [];
        } catch (\PDOException $e) {
            return [[], []];
        }

        return [$explain, $warnings];
    }
}
