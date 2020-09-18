<?php


namespace App\InMemoryAggregators;

use App\Aggregators\LogAggregatorInterface;

class Aggregator implements LogAggregatorInterface
{

    private $logFileContentOriginNormalized;


    public function __construct(string $logFileStingContent)
    {
        $this->logFileContentOrigin = $logFileStingContent;

        $this->logFileContentOriginNormalized = $this->normolizeEOL($logFileStingContent);
    }

    private function getAllAccessRecords() {

        $allAccessRecords = explode(PHP_EOL, $this->logFileContentOriginNormalized);

        return $allAccessRecords = array_map(function ($record) {

            return $line = str_replace(array("\n", "\r"), '', $record);

        }, $allAccessRecords);
    }

    public function getGroupByRoutes() {

        $allAccessRecords = $this->getAllAccessRecords();

        $groupedByRoutes = [];

        foreach ($allAccessRecords as $record) {

            $recordParts = explode(' ',$record);
            $groupedByRoutes[$recordParts[0]][] = $recordParts[1];
        }

        return $groupedByRoutes;
    }

    public function groupByRouteCountIpsOrderByIps(bool $uniqueIps = false) {

        $groupedByRoutes = $this->getGroupByRoutes();

        $groupedByRoutesAndAggregated = [];

        foreach ($groupedByRoutes as $route => $ips) {

            $ips = $uniqueIps ? array_unique($ips) : $ips;

            $groupedByRoutesAndAggregated[] = [
                'route' => $route,
                'ips' => count($ips)
            ];
        }

        uasort($groupedByRoutesAndAggregated, function ($routeGroupOne, $routeGroupTwo)
        {
            if ($routeGroupOne['ips'] == $routeGroupTwo['ips']) {
                return (strlen($routeGroupOne['route']) < strlen($routeGroupTwo['route'])) ? 1 : -1;
            }
            return ($routeGroupOne['ips'] < $routeGroupTwo['ips']) ? 1 : -1;
        });

        return $groupedByRoutesAndAggregated;
    }

    public function aggregateLogs(): array
    {
        return $this->groupByRouteCountIpsOrderByIps();
    }

    public function aggregateLogsUniqueIps():array
    {
        return $this->groupByRouteCountIpsOrderByIps(true);
    }

    private function normolizeEOL($content) {

        switch (PHP_OS_FAMILY) {
            case 'Windows':
                $needle = "\n";
                break;
            case 'Linux':
                $needle = "\r\n";
                break;
            default:
                $needle = PHP_EOL;
        }

        $pattern = sprintf('/%s/i', $needle);

        return preg_replace($pattern, PHP_EOL, trim($content));
    }
}
