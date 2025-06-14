<?php

declare(strict_types=1);

namespace Prism\Prism\Events;

class PrismRequestCompleted extends TelemetryEvent
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function __construct(
        public readonly string $provider = '',
        public readonly array $attributes = [],
        public readonly ?\Throwable $exception = null
    ) {}
}
