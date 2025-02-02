<?php

namespace App\EventListener\Product\Inventory;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Interop\Queue\Processor;
use InvalidArgumentException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UpdateInventoryProductCommandHandler
{
    public function __construct(private readonly EntityManagerInterface $entityManager, private readonly ProductRepository $productRepository)
    {
    }

    public function __invoke(UpdateInventoryProductCommand $command)
    {
        try {
            $payload = json_decode($command->content(), true);

            dump($payload);
            $data = $payload['data'] ?? null;
            if (!$data || !isset($data['id'])) {
                throw new InvalidArgumentException('Invalid message payload: missing "data" or "product_id".');
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
        } catch (Exception $e) {
            return Processor::REJECT;
        }
    }
}