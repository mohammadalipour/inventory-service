<?php

namespace App\EventListener\Product\Inventory;


class UpdateInventoryProductCommand
{
    public function __construct(private readonly string $content)
    {
    }

    public function content():string
    {
        return $this->content;
    }
}