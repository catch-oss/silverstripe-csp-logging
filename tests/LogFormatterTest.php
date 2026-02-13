<?php

namespace Camspiers\CSP\Tests;

use Camspiers\CSP\LogFormatter;
use Monolog\Level;
use Monolog\LogRecord;
use PHPUnit\Framework\TestCase;

class LogFormatterTest extends TestCase
{
    private LogFormatter $formatter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->formatter = new LogFormatter();
    }

    public function testFormatProducesCatchStandardOutput(): void
    {
        $datetime = new \DateTimeImmutable('2024-01-10 12:00:00');
        $record = new LogRecord(
            datetime: $datetime,
            channel: 'Camspiers.CSP',
            message: 'Content-Security-Policy violation',
            level: Level::Info,
            context: ['blocked-uri' => 'http://example.com/script.js'],
        );

        $output = $this->formatter->format($record);

        $this->assertStringStartsWith('[2024-01-10 12:00:00] INFO Camspiers.CSP - Content-Security-Policy violation', $output);
        $this->assertStringContainsString('"blocked-uri":"http:\/\/example.com\/script.js"', $output);
        $this->assertStringEndsWith("\n", $output);
    }

    public function testFormatWithEmptyContext(): void
    {
        $datetime = new \DateTimeImmutable('2024-01-10 12:00:00');
        $record = new LogRecord(
            datetime: $datetime,
            channel: 'Camspiers.CSP',
            message: 'Test message',
            level: Level::Error,
            context: [],
        );

        $output = $this->formatter->format($record);

        $this->assertSame("[2024-01-10 12:00:00] ERROR Camspiers.CSP - Test message\n", $output);
    }

    public function testFormatBatchConcatenatesRecords(): void
    {
        $datetime = new \DateTimeImmutable('2024-01-10 12:00:00');
        $records = [
            new LogRecord(
                datetime: $datetime,
                channel: 'Camspiers.CSP',
                message: 'First',
                level: Level::Info,
                context: [],
            ),
            new LogRecord(
                datetime: $datetime,
                channel: 'Camspiers.CSP',
                message: 'Second',
                level: Level::Warning,
                context: [],
            ),
        ];

        $output = $this->formatter->formatBatch($records);

        $this->assertStringContainsString('First', $output);
        $this->assertStringContainsString('Second', $output);
        $this->assertSame(2, substr_count($output, "\n"));
    }
}
