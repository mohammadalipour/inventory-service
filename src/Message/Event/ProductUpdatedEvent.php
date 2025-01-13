<?php

namespace App\Message\Event;

class ProductUpdatedEvent
{
    private int $productId;
    private string $status;

    public function __construct(int $productId, string $status)
    {
        $this->productId = $productId;
        $this->status = $status;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}

