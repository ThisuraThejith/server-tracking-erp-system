<?php

namespace App\Repository;

use App\Entity\ServerRam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ServerRam>
 *
 * @method ServerRam|null find($id, $lockMode = null, $lockVersion = null)
 * @method ServerRam|null findOneBy(array $criteria, array $orderBy = null)
 * @method ServerRam[]    findAll()
 * @method ServerRam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServerRamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ServerRam::class);
    }

    public function add(ServerRam $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ServerRam $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
