<?php


namespace App\Aggregators;

/**
 * Interface LogAggregatorInterface
 * @package App\Aggregators
 */
interface LogAggregatorInterface
{
    /**
     * Groups records by route and aggregates all ips
     * @return array
     */
    public function aggregateLogs(): array;

    /**
     * Groups records by route and aggregates only unique ips
     *
     * @return array
     */
    public function aggregateLogsUniqueIps(): array;
}
