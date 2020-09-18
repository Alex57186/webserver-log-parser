<?php


namespace App\InMemoryAggregators;

use App\Aggregators\LogAggregatorInterface;

/**
 * UNIMPLEMENTED
 * Supposed to process the log file by chunks instead of how it is done in InMemoryAggregators\Aggregator which loads the entire file in memory
 * Class AggregatorChunks
 * @package App\InMemoryAggregators
 */
class AggregatorChunks implements LogAggregatorInterface
{
    public function aggregateLogs(): array
    {
        // TODO: Implement aggregateLogs() method.
    }

    public function aggregateLogsUniqueIps(): array
    {
        // TODO: Implement aggregateLogsUniqueIps() method.
    }
}
