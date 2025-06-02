<?php

declare(strict_types=1);

namespace Prism\Prism\Concerns;

use Closure;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

trait InitializesClient
{
    abstract protected function url(): string;

    /**
     * @return array<string, string>
     */
    protected function headers(): array
    {
        return [];
    }

    protected function token(): string
    {
        return '';
    }

    /**
     * @param  array<string, mixed>  $options
     * @param  array{}|array{0: array<int, int>|int, 1?: Closure|int, 2?: ?callable, 3?: bool}  $retry
     */
    protected function client(array $options = [], array $retry = [], ?string $baseUrl = null): PendingRequest
    {
        return Http::when($this->token() !== '', fn ($client) => $client->withToken($this->token()))
            ->when($this->headers() !== [], fn ($client) => $client->withHeaders($this->headers()))
            ->withOptions($options)
            ->when($retry !== [], fn ($client) => $client->retry(...$retry))
            ->baseUrl($baseUrl ?? $this->url());
    }
}
