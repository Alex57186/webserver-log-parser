<?php


namespace App\Aggregators\DBAggregator;

use App\Aggregators\LogAggregatorInterface;
use Illuminate\Support\Facades\DB;

/**
 * Class Aggregator
 *
 * Aggregates logs with the usage of db,
 * as a constructor parameter takes ParserInterface instance
 * The idea is that log file can be parsed in many different ways,
 * so it makes sense to separate parsing logic and aggregation logic,
 * It enables to use different implementations of parser
 *
 * @package App\Aggregators\DBAggregator
 */
class DBAggregator implements LogAggregatorInterface
{
    /**
     * Table from which logs are taken
     * @var string
     */
    private $tableName;

    /**
     *
     * @param $res
     * @return array
     */
    private function resultToArray($res) {
        return array_map(function ($row) {
            return (array)$row;
        },$res);
    }

    /**
     * DBAggregator constructor.
     * @param ParserInterface $logParser
     */
    public function __construct(ParserInterface $logParser)
    {
        $this->tableName = $logParser->getTableName();

        $logParser->parse();
    }

    /**
     * Groups records by route and aggregates all ips
     * @return array
     */
    public function aggregateLogs(): array
    {
        $res = DB::table($this->tableName)
            ->select('route', DB::raw('count(ip) as ips'))
            ->orderBy('ips', 'DESC')
            ->orderBy( DB::raw('length(route)'), 'DESC')
            ->groupBy(['route'])
            ->get()->toArray();

        return $this->resultToArray($res);
    }

    /**
     * Groups records by route and aggregates only unique ips
     * @return array
     */
    public function aggregateLogsUniqueIps(): array
    {
        $res = DB::table($this->tableName)
            ->select('route', DB::raw('count(DISTINCT ip) as ips'))
            ->orderBy('ips', 'DESC')
            ->orderBy( DB::raw('length(route)'), 'DESC')
            ->groupBy(['route'])
            ->get()->toArray();

        return $this->resultToArray($res);
    }
}
