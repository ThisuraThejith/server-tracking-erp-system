<?php

namespace App\Repository;

use App\Entity\Ram;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ram>
 *
 * @method Ram|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ram|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ram[]    findAll()
 * @method Ram[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ram::class);
    }

    public function add(Ram $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Ram $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAllRams(): array
    {
        $q = $this->createQueryBuilder('r')
            ->select('r');
        return $q->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function getRamById(int $ramId): Ram
    {
        $q = $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.id = :ramId')
            ->setParameter('ramId', $ramId);
        return $q->getQuery()->getSingleResult();
    }
}
