<?php


namespace App\Aggregators;

interface LogAggregatorInterface
{
    public function aggregateLogs(): array;
    public function aggregateLogsUniqueIps(): array;
}
