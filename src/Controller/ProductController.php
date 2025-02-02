<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/api/product/{id}/enable', name: 'api_product_enable', methods: ['PUT'])]
    public function enableProduct(int $id): JsonResponse
    {
        return $this->changeProductEnabled($id, true);
    }

    #[Route('/api/product/{id}/disable', name: 'api_product_disable', methods: ['PUT'])]
    public function disableProduct(int $id): JsonResponse
    {
        return $this->changeProductEnabled($id, false);
    }

    private function changeProductEnabled(int $id, bool $enabled): JsonResponse
    {
        $this->entityManager->beginTransaction();

        try {
            $product = $this->entityManager->getRepository(Product::class)->find($id);

            if (!$product) {
                return $this->json(['error' => 'Product not found'], Response::HTTP_NOT_FOUND);
            }

            // Update product status
            $product->setEnabled($enabled);
            $this->entityManager->flush();

            $messageBody = json_encode(['data'=>['product_id' => $product->getId(), 'enabled' => $product->isEnabled()]]);
//            $message = new Message($messageBody, ['Content-Type' => 'application/json']);
//            $this->producer->sendEvent('service_shopping_queue', $message);

            // Commit transaction
            $this->entityManager->commit();

            return $this->json([
                'message' => 'Product status updated successfully',
                'product' => [
                    'id' => $product->getId(),
                    'enabled' => $product->isEnabled(),
                ],
            ]);
        } catch (\Throwable $e) {
            $this->entityManager->rollback();

            return $this->json(['error' => 'An error occurred while updating the product status'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
