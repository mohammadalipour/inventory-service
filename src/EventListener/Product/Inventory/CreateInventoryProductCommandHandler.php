<?php

namespace App\EventListener\Product\Inventory;

use App\Entity\Product;
use App\Entity\ProductStatus;
use Doctrine\ORM\EntityManagerInterface;
use Interop\Queue\Processor;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class CreateInventoryProductCommandHandler
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function __invoke(CreateInventoryProductCommand $command)
    {
        try {
            $payload = json_decode($command->content(), true);

            dump($payload);
            $data = $payload['data'] ?? null;
            if (!$data || !isset($data['id']) || !isset($data['enabled'])) {
                throw new \InvalidArgumentException('Invalid message payload: missing "data" or "product_id".');
            }
            $productId = $data['id'];
            $enabled = $data['enabled'];

            $product = new Product();
            $product->setProductId($productId)
                ->setQuantityInStock(0)
                ->setEnabled($enabled)
                ->setReservedQuantity(0)
                ->setStatus(ProductStatus::OUT_OF_STOCK);

            $this->entityManager->persist($product);
            $this->entityManager->flush();

            return Processor::ACK;
        } catch (\Exception $e) {
            return Processor::REJECT;
        }
    }
}