<?php

namespace App\Message\Event\Product;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Interop\Queue\Context;
use Interop\Queue\Message;
use Interop\Queue\Processor;

readonly class ProductUpdatedEvent
{
    public function __construct(private ProductRepository $productRepository, private EntityManagerInterface $entityManager)
    {
    }

    public function process(Message $message, Context $context): string
    {
        try {
            $payload = json_decode($message->getBody(), true);

            dump($payload);
            $data = $payload['data'] ?? null;
            if (!$data || !isset($data['id'])) {
                throw new \InvalidArgumentException('Invalid message payload: missing "data" or "product_id".');
            }

            $productId = $data['id'];
            $enabled = $data['enabled'];
            $isDeleted = $data['is_deleted'];
            $updated = 0;

            $product = $this->productRepository->findBy(['productId' => $productId]);
            if ($enabled) {
                $updated++;
                $product->setEnabled($enabled);
            }

            if ($isDeleted) {
                $updated++;
                $product->setSoftDelete($isDeleted);
            }

            if ($product && $updated > 0) {
                $this->entityManager->persist($product);
                $this->entityManager->flush();
            }

            return Processor::ACK;
        } catch (\Exception $e) {
            return Processor::REJECT;
        }
    }
}

