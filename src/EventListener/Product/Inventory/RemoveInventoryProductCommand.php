<?php

namespace App\EventListener\Product\Inventory;

class RemoveInventoryProductCommand
{
    public function __construct(private readonly string $content)
    {
    }

    public function content():string
    {
        return $this->content;
    }
}