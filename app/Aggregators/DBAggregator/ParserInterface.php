<?php


namespace App\Aggregators\DBAggregator;

interface ParserInterface
{
    public function __construct(string $filePath, string $tableName);

    public function parse();

    public function getTableName(): string;
}
