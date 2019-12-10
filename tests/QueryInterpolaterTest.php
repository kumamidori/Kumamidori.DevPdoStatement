<?php

declare(strict_types=1);

namespace Kumamidori\DevPdoStatement;

use PHPUnit\Framework\TestCase;

class QueryInterpolaterTest extends TestCase
{
    /**
     * @var QueryInterpolater
     */
    private $queryInterpolater;

    protected function setUp(): void
    {
        parent::setUp();
        $this->queryInterpolater = new QueryInterpolater();
    }

    /**
     * @dataProvider getSimpleQueryData
     */
    public function testInterpolateCaseSimpleQuery($query, $params, $expected)
    {
        $result = $this->queryInterpolater->interpolate($query, $params);
        $this->assertSame($expected, $result);
    }

    public function getSimpleQueryData()
    {
        return [
            [
                'INSERT INTO user(id, name) VALUES (:id, :name)',
                [
                    'id' => 1,
                    'name' => 'abc',
                ],
                "INSERT INTO user(id, name) VALUES (1, 'abc')",
            ],
        ];
    }
}
