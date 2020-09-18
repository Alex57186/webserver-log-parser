<?php


namespace App;


class LogCliFormatter
{
    public function formatAsLine($accessLogGroupedByRoute) {

        foreach ($accessLogGroupedByRoute as $row) {
            $row = (array)$row;
            $sortedByIpsAmountStrings[] = sprintf('%s %d visits', $row['route'], $row['ips']);
        }

        $inputLine = implode(' ', $sortedByIpsAmountStrings);

        return $inputLine;
    }
}
