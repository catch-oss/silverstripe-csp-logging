<?php

namespace Camspiers\CSP;

use Monolog\Formatter\FormatterInterface;
use Monolog\LogRecord;

class LogFormatter implements FormatterInterface
{
    public function format(LogRecord $record): string
    {
        $output = (
            '[' . date('Y-m-d H:i:s') . '] ' .
            $record->channel . '.' . $record->level->getName() . ': ' .
            $record->message . ': ' .
            json_encode($record->context ?? '', JSON_PRETTY_PRINT)
        );

        return $output;
    }

    public function formatBatch(array $records): string
    {
        $output = '';
        foreach ($records as $record) {
            $output .= $this->format($record);
        }
        return $output;
    }
}
