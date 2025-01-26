<?php

namespace App\Message\Event\Product;

use App\Entity\Product;
use App\Entity\ProductStatus;
use Doctrine\ORM\EntityManagerInterface;
use Interop\Queue\Context;
use Interop\Queue\Message;
use Interop\Queue\Processor;

readonly class ProductCreatedEvent implements Processor
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function process(Message $message, Context $context): string
    {
        try {
            $payload = json_decode($message->getBody(), true);

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

