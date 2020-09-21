<?php


namespace App;

/**
 * Class LogCliFormatter
 *
 * LogCliFormatter is responsible for formation
 * of aggregated data to print it in console
 *
 * @package App
 */
class LogCliFormatter
{
    /**
     * @param $accessLogGroupedByRoute
     * @return string
     */
    public function formatAsLine($accessLogGroupedByRoute) {

        foreach ($accessLogGroupedByRoute as $row) {
            $row = (array)$row;
            $sortedByIpsAmountStrings[] = sprintf('%s %d visits', $row['route'], $row['ips']);
        }

        $inputLine = implode(' ', $sortedByIpsAmountStrings);

        return $inputLine;
    }
}
