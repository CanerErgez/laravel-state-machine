<?php

namespace Caner\StateMachine\Support;

final readonly class TransitionContext
{
    /**
     * @param  array<string, mixed>  $data
     * @param  array<string, mixed>  $metadata
     */
    public function __construct(
        public array $data = [],
        public mixed $actor = null,
        public array $metadata = [],
    ) {}

    /** @param array<string, mixed> $data */
    public function withData(array $data): self
    {
        return new self($data, $this->actor, $this->metadata);
    }

    /** @param array<string, mixed> $data */
    public function mergeData(array $data): self
    {
        return $this->withData(array_merge($this->data, $data));
    }

    /** @param array<string, mixed> $metadata */
    public function withMetadata(array $metadata): self
    {
        return new self($this->data, $this->actor, $metadata);
    }
}
