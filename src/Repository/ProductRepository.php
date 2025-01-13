<?php
namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    // Custom query to fetch products with their warehouse info
    public function findAllWithWarehouse()
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.warehouse', 'w') // Joining with the warehouse
            ->addSelect('w') // Selecting warehouse data
            ->getQuery()
            ->getResult();
    }

    // Custom query to find products by warehouse
    public function findByWarehouse($warehouseId)
    {
        return $this->createQueryBuilder('p')
            ->where('p.warehouse = :warehouseId')
            ->setParameter('warehouseId', $warehouseId)
            ->getQuery()
            ->getResult();
    }
}
