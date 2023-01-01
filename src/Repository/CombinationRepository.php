<?php

namespace App\Repository;

use App\Entity\Combination;
use App\Entity\Meme;
use App\Entity\Tag;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use mysql_xdevapi\Collection;

/**
 * @extends ServiceEntityRepository<Combination>
 *
 * @method Combination|null find($id, $lockMode = null, $lockVersion = null)
 * @method Combination|null findOneBy(array $criteria, array $orderBy = null)
 * @method Combination[]    findAll()
 * @method Combination[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CombinationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Combination::class);
    }

    public function save(Combination $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Combination $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Combination[]
     */
    public function getAllUserCombination(User $user): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function removeTag(Tag $tag, Meme $meme, User $user): void
    {
        $this->createQueryBuilder('c')
            ->delete()
            ->andWhere('c.tag = :tag')
            ->andWhere('c.meme = :meme')
            ->andWhere('c.user = :user')
            ->setParameter('tag', $tag)
            ->setParameter('user', $user)
            ->setParameter('meme', $meme)
            ->getQuery()
            ->execute();

        $this->getEntityManager()->flush();
    }

    public function removeUserMeme(Meme $meme, User $user): void
    {
        $this->createQueryBuilder('c')
            ->delete()
            ->andWhere('c.meme = :meme')
            ->andWhere('c.user = :user')
            ->setParameter('meme', $meme)
            ->setParameter('user', $user)
            ->getQuery()
            ->execute();

        $this->getEntityManager()->flush();
    }

    public function searchByTags(array $tags, User $user): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.tag IN (:tags)')
            ->andWhere('c.user = :user')
            ->setParameter('tags', $tags)
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
//        $this->findBy([
//            'tag_id' => $tags
//        ]);
    }

}
