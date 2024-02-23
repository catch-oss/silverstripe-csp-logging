<?php

namespace Camspiers\CSP;

use Monolog\Formatter\FormatterInterface;
use Monolog\LogRecord;

class LogFormatter implements FormatterInterface
{
    /**
     * Undocumented function
     *
     * @param array $record
     * @return string
     */
    public function format(LogRecord $record)
    {
        $output = (
            '[' . date('Y-m-d H:i:s') . '] ' .
            $record['channel'] . '.' . $record['level_name'] . ': ' .
            $record['message'] . ': ' .
            json_encode($record['context'], JSON_PRETTY_PRINT)
        );

        return $output;
    }

    /**
     * Formats a set of log records.
     *
     * @param  array $records A set of records to format
     * @return string The formatted set of records
     */
    public function formatBatch(array $records)
    {
        $output = '';
        foreach ($records as $record) {
            $output .= $this->format($record);
        }
        return $output;
    }
}
