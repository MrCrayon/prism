<?php

namespace Prism\Prism\Providers\VoyageAI;

use Generator;
use Prism\Prism\Concerns\InitializesClient;
use Prism\Prism\Contracts\Provider;
use Prism\Prism\Embeddings\Request as EmbeddingRequest;
use Prism\Prism\Embeddings\Response as EmbeddingsResponse;
use Prism\Prism\Enums\Provider as ProviderName;
use Prism\Prism\Exceptions\PrismException;
use Prism\Prism\Rerank\PendingRequest as PendingRerankRequest;
use Prism\Prism\Rerank\Request as RerankRequest;
use Prism\Prism\Rerank\Response as RerankResponse;
use Prism\Prism\Structured\Request as StructuredRequest;
use Prism\Prism\Structured\Response as StructuredResponse;
use Prism\Prism\Text\Request as TextRequest;
use Prism\Prism\Text\Response as TextResponse;

class VoyageAI implements Provider
{
    use InitializesClient;

    public function __construct(
        #[\SensitiveParameter] protected string $apiKey,
        protected string $baseUrl
    ) {}

    #[\Override]
    public function text(TextRequest $request): TextResponse
    {
        throw PrismException::unsupportedProviderAction(__METHOD__, class_basename($this));
    }

    #[\Override]
    public function structured(StructuredRequest $request): StructuredResponse
    {
        throw PrismException::unsupportedProviderAction(__METHOD__, class_basename($this));
    }

    #[\Override]
    public function embeddings(EmbeddingRequest $request): EmbeddingsResponse
    {
        $handler = new Embeddings($this->client(
            $request->clientOptions(),
            $request->clientRetry()
        ));

        return $handler->handle($request);
    }

    public static function reranks(string $model): PendingRerankRequest
    {
        return (new PendingRerankRequest)
            ->using(ProviderName::VoyageAI, $model);
    }

    public function rerank(RerankRequest $request): RerankResponse
    {
        $handler = new Reranks($this->client(
            $request->clientOptions(),
            $request->clientRetry()
        ));

        return $handler->handle($request);
    }

    #[\Override]
    public function stream(TextRequest $request): Generator
    {
        throw PrismException::unsupportedProviderAction(__METHOD__, class_basename($this));
    }

    protected function url(): string
    {
        return $this->baseUrl;
    }

    protected function token(): string
    {
        return $this->apiKey;
    }
}
