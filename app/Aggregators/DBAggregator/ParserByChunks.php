<?php


namespace App\Aggregators\DBAggregator;

/**
 * UNIMPLEMENTED
 * Supposed to process the log file by chunks instead of how it is done in DBAggregator\ParserByLines.php
 * Class ParserByChanks
 * @package App\Aggregators\DBAggregator
 */
class ParserByChunks implements ParserInterface
{
    public function __construct(string $filePath, $tableName)
    {
    }

    public function getTableName(): string {
    }

    public function parse() {
    }
}
