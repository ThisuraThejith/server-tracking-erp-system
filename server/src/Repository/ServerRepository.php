<?php

namespace App\Repository;

use App\Entity\Server;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Server>
 *
 * @method Server|null find($id, $lockMode = null, $lockVersion = null)
 * @method Server|null findOneBy(array $criteria, array $orderBy = null)
 * @method Server[]    findAll()
 * @method Server[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ServerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Server::class);
    }

    public function add(Server $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Server $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAllServers(): array
    {
        $q = $this->createQueryBuilder('s')
            ->select('s, sr, r')
            ->leftJoin('s.serverRams', 'sr')
            ->leftJoin('sr.ram', 'r');
        return $q->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function getServerByAssetId(int $assetId): Server
    {
        $q = $this->createQueryBuilder('s')
            ->select('s')
            ->where('s.assetId = :assetId')
            ->setParameter('assetId', $assetId);
        return $q->getQuery()->getSingleResult();
    }
}
