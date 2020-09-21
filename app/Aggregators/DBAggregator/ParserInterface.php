<?php


namespace App\Aggregators\DBAggregator;

/**
 * Interface ParserInterface
 * Basci interface for the different parser implementations
 * @package App\Aggregators\DBAggregator
 */
interface ParserInterface
{
    /**
     * ParserInterface constructor.
     * @param string $filePath
     * @param string $tableName
     */
    public function __construct(string $filePath, string $tableName);

    /**
     * @return mixed
     */
    public function parse();

    /**
     * @return string
     */
    public function getTableName(): string;
}
