<?php

namespace Caner\StateMachine\Support;

final readonly class TransitionContext
{
    public function __construct(
        public array $data = [],
        public mixed $actor = null,
        public array $metadata = [],
    ) {
    }

    public function withData(array $data): self
    {
        return new self($data, $this->actor, $this->metadata);
    }

    public function mergeData(array $data): self
    {
        return $this->withData(array_merge($this->data, $data));
    }
}
