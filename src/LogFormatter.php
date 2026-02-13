<?php

namespace Camspiers\CSP;

use Monolog\Formatter\FormatterInterface;
use Monolog\LogRecord;

class LogFormatter implements FormatterInterface
{
    public function format(LogRecord $record): string
    {
        $context = $record->context ? ' ' . json_encode($record->context) : '';

        return sprintf(
            "[%s] %s %s - %s%s\n",
            $record->datetime->format('Y-m-d H:i:s'),
            $record->level->getName(),
            $record->channel,
            $record->message,
            $context,
        );
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
