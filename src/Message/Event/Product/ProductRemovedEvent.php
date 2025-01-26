<?php

namespace App\Message\Event\Product;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Interop\Queue\Context;
use Interop\Queue\Message;
use Interop\Queue\Processor;

readonly class ProductRemovedEvent
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

            $product = $this->productRepository->findBy(['productId'=>$productId]);
            $this->entityManager->remove($product);
            $this->entityManager->flush();

            return Processor::ACK;
        } catch (\Exception $e) {
            return Processor::REJECT;
        }
    }
}

