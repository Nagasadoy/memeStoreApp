<?php

namespace App\Repository;

use App\DTO\FilterMemeByTagDTO;
use App\Entity\Meme\Meme;
use App\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Meme>
 *
 * @method Meme|null find($id, $lockMode = null, $lockVersion = null)
 * @method Meme|null findOneBy(array $criteria, array $orderBy = null)
 * @method Meme[]    findAll()
 * @method Meme[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MemeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Meme::class);
    }

    public function save(Meme $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Meme $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getUserMemes(User $user, FilterMemeByTagDTO $filter)
    {
        $qb = $this->createQueryBuilder('m')
            ->leftJoin('m.tags', 't')
            ->andWhere('m.user = :user')
            ->setParameter(':user', $user);

        if (null !== $filter->getTagName()) {
            $qb->andWhere('t.name = :tagName')
                ->setParameter('tagName', $filter->getTagName());
        }

        return $qb->getQuery()->getResult();
    }
}
