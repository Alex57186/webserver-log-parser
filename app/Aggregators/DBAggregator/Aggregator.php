<?php


namespace App\Aggregators\DBAggregator;

use App\Aggregators\LogAggregatorInterface;
use Illuminate\Support\Facades\DB;

class Aggregator implements LogAggregatorInterface
{
    private $tableName;

    private function resultToArray($res) {
        return array_map(function ($row) {
            return (array)$row;
        },$res);
    }

    public function __construct(ParserInterface $logParser)
    {
        $this->tableName = $logParser->getTableName();

        $logParser->parse();
    }

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
