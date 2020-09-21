<?php


namespace App\Aggregators\DBAggregator;

/**
 * UNIMPLEMENTED
 * Supposed to process the log file by chunks instead of how it is done in DBAggregator\ParserByLines.php
 * Class ParserByChunks
 * @package App\Aggregators\DBAggregator
 */
class ParserByChunks implements ParserInterface
{
    /**
     * ParserByChunks constructor.
     * @param string $filePath
     * @param $tableName
     */
    public function __construct(string $filePath, $tableName)
    {
    }

    /**
     * @return string
     */
    public function getTableName(): string {
    }

    /**
     *
     */
    public function parse() {
    }
}
