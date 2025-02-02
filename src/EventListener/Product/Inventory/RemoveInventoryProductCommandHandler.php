<?php

namespace App\EventListener\Product\Inventory;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Interop\Queue\Processor;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class RemoveInventoryProductCommandHandler
{
    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly ProductRepository $productRepository)
    {
    }

    public function __invoke(RemoveInventoryProductCommand $command)
    {
        try {
            $payload = json_decode($command->content(), true);

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