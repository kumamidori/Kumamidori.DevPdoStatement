<?php

declare(strict_types=1);

namespace Kumamidori\DevPdoStatement;

use PHPUnit\Framework\TestCase;

class DevPdoStatementTest extends TestCase
{
    /**
     * @var \PDO
     */
    private $pdo;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var DevPdoStatement
     */
    private $sth;

    protected function setUp():void
    {
        parent::setUp();
        $this->pdo = new \PDO('mysql:host=localhost;', 'root');
        $this->logger = new Logger;
        $this->pdo->exec('CREATE DATABASE IF NOT EXISTS tmp;');
        $this->pdo->exec('USE tmp;');
        $this->pdo->exec('CREATE TABLE user(id integer, name text)');
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(\PDO::ATTR_STATEMENT_CLASS, [DevPdoStatement::class, [$this->pdo, $this->logger]]);
    }

    protected function tearDown():void
    {
        parent::tearDown();
        $this->pdo->exec('DROP DATABASE tmp;');
    }

    public function testInterpolateQuery()
    {
        $this->pdo->setAttribute(\PDO::ATTR_STATEMENT_CLASS, [DevPdoStatement::class, [$this->pdo, $this->logger]]);
        $sth = $this->pdo->prepare('INSERT INTO user(id, name) VALUES (:id, :name)');
        $this->assertInstanceOf('\Kumamidori\DevPdoStatement\DevPdoStatement', $sth);
    }

    public function testBindValue()
    {
        $sth = $this->pdo->prepare('INSERT INTO user(id, name) VALUES (:id, :name)');
        $sth->bindValue('id', 1, \PDO::PARAM_INT);
        $sth->bindValue('name', 'yamane', \PDO::PARAM_STR);
        $sth->execute();
        $this->assertSame("INSERT INTO user(id, name) VALUES (1, 'yamane')", $sth->interpolateQuery);
    }

    public function testBindParam()
    {
        $sth = $this->pdo->prepare('INSERT INTO user(id, name) VALUES (:id, :name)');
        $id = $name  = '';
        $sth->bindParam('id', $id, \PDO::PARAM_STR);
        $sth->bindParam('name', $name, \PDO::PARAM_STR);
        $id = 1;
        $name = 'yamane';
        $sth->execute();
        $this->assertSame("INSERT INTO user(id, name) VALUES (1, 'yamane')", $sth->interpolateQuery);
    }
}
