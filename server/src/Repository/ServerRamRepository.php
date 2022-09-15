<?php

namespace App\Repository;

use App\Entity\Ram;
use App\Entity\Server;
use App\Entity\ServerRam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
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

    public function add(ServerRam $entity, bool $flush = true): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ServerRam $entity, bool $flush = true): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param ServerRam $serverRam
     * @return ServerRam
     */
    public function persistServerRam(ServerRam $serverRam): ServerRam
    {
        $this->getEntityManager()->persist($serverRam);
        return $serverRam;
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    public function getServerRamByServer(Server $server): array
    {
        $q = $this->createQueryBuilder('sr')
            ->select('sr')
            ->where('sr.server = :server')
            ->setParameter('server', $server);
        return $q->getQuery()->getResult();
    }

    public function getServerRamByRamAndServer(Ram $ram, Server $server): ServerRam
    {
        $q = $this->createQueryBuilder('sr')
            ->select('sr')
            ->where('sr.ram = :ram')
            ->andWhere('sr.server = :server')
            ->setParameter('ram', $ram)
            ->setParameter('server', $server);
        return $q->getQuery()->getSingleResult();
    }

    public function getServerRamsAndRamsOfServer(Server $server): array
    {
        $q = $this->createQueryBuilder('sr')
            ->select('sr, r')
            ->leftJoin('sr.ram', 'r')
            ->where('sr.server = :server')
            ->setParameter('server', $server);
        return $q->getQuery()->getResult(Query::HYDRATE_ARRAY);
    }
}
