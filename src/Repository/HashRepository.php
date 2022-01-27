<?php

namespace App\Repository;

use App\DTO\PaginationDTO;
use App\DTO\ResponseObjectDTO;
use App\Entity\Hash;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Hash|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hash|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hash[]    findAll()
 * @method Hash[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HashRepository extends ServiceEntityRepository
{
    private PaginatorInterface $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
        parent::__construct($registry, Hash::class);
    }

    /**
     * @param string|null $attempts
     * @param int|null $page
     * @param int|null $limit
     * @return PaginationDTO
     */
    public function getAllHash(string $attempts = null, int $page = null, int $limit = null): PaginationDTO
    {
        $qb = $this->createQueryBuilder('hash');

        if ($attempts && ctype_digit($attempts)) {
            $qb->where("hash.attempts <  ${attempts}");
        }

        if (!$page || !is_int($page)) {
            $page = 1;
        }

        if (!$limit || !is_int($limit)) {
            $limit = 25;
        }

        $query = $qb->getQuery();
        $result = $this->paginator->paginate($query, $page, $limit);

        return new PaginationDTO(data: $result->getItems(),
            total_items: $result->getTotalItemCount(),
            items_per_page: $result->getItemNumberPerPage(),
            page: $page,
            limit: $limit);
    }

    /**
     * @param ResponseObjectDTO $hashDto
     * @param int $block
     * @param string $string
     * @return Hash
     */
    public function addHash(ResponseObjectDTO $hashDto, int $block, string $string): Hash
    {
        $entityManager = $this->getEntityManager();
        $hash = new Hash();
        $hash->setAttempts($hashDto->getCount());
        $hash->setHash($hashDto->getRandomHash());
        $hash->setKeyFound($hashDto->getRandomKey());
        $hash->setBlockNumber($block);
        $hash->setInputString($string);
        $hash->setTimeStamp((new \DateTime()));

        $entityManager->persist($hash);
        $entityManager->flush();

        return $hash;
    }

}
