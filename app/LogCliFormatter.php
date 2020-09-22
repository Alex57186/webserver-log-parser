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
    public function formatAsLine($accessLogGroupedByRoute, bool $unique = false) {

        foreach ($accessLogGroupedByRoute as $row) {
            $row = (array)$row;
            $format = $unique ? '%s %d unique visits':'%s %d visits';
            $sortedByIpsAmountStrings[] = sprintf($format, $row['route'], $row['ips']);
        }

        $inputLine = implode(' ', $sortedByIpsAmountStrings);

        return $inputLine;
    }
}
