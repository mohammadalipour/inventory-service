<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: 'App\Repository\ProductRepository')]
#[ORM\Table(name: 'products')]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'integer')]
    private int $productId;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $quantityInStock = 0;

    #[ORM\Column(type: 'integer', options: ['default' => 0])]
    private int $reservedQuantity = 0;

    #[ORM\ManyToOne(targetEntity: Warehouse::class)]
    #[ORM\JoinColumn(name: 'warehouse_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Warehouse $warehouseId;

    #[ORM\Column(type: 'string', enumType: ProductStatus::class, columnDefinition: 'ENUM("in_stock", "out_of_stock", "backordered")')]
    private ProductStatus $status;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private bool $enabled = true;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $createdAt;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        $this->status = ProductStatus::IN_STOCK;
    }

    // Getters and Setters

    public function getId(): int
    {
        return $this->id;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function setProductId(int $productId): self
    {
        $this->productId = $productId;
        return $this;
    }

    public function getQuantityInStock(): int
    {
        return $this->quantityInStock;
    }

    public function setQuantityInStock(int $quantityInStock): self
    {
        $this->quantityInStock = $quantityInStock;
        return $this;
    }

    public function getReorderLevel(): int
    {
        return $this->reorderLevel;
    }

    public function setReorderLevel(int $reorderLevel): self
    {
        $this->reorderLevel = $reorderLevel;
        return $this;
    }

    public function getReservedQuantity(): int
    {
        return $this->reservedQuantity;
    }

    public function setReservedQuantity(int $reservedQuantity): self
    {
        $this->reservedQuantity = $reservedQuantity;
        return $this;
    }

    public function getWarehouseId(): Warehouse
    {
        return $this->warehouseId;
    }

    public function setWarehouseId(Warehouse $warehouseId): self
    {
        $this->warehouseId = $warehouseId;
        return $this;
    }

    public function getStatus(): ProductStatus
    {
        return $this->status;
    }

    public function setStatus(ProductStatus $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;
        return $this;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}