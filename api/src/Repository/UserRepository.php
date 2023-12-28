<?php

namespace App\Repository;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr\Andx;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, User::class);
    }

    public function findActiveUsersCreatedInLastWeek()
    {
        $lastWeekDate = new DateTime('-7 days');

        return $this->entityManager
            ->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u')
            ->where(
                (new Andx())
                    ->add('u.active = 1')
                    ->add('u.createdAt >= :lastWeekDate')
            )
            ->setParameter('lastWeekDate', $lastWeekDate)
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(User $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(User $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param array $criteria
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneItemBy(array $criteria): ?User
    {

        $qb = $this->entityManager
            ->createQueryBuilder()
            ->select('u')
            ->from(User::class, 'u');

        foreach ($criteria as $fieldName => $fieldValue) {
            $qb->where(sprintf('u.%s = :%s', $fieldName, $fieldName))
                ->setParameter(sprintf(':%s', $fieldName), $fieldValue);
        }

        return $qb->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Build query criteria
     *
     * @param array $criteria
     * @return Andx
     */
    private function buildCriteria(array $criteria): Expr\Andx
    {
        $andX = new Expr\Andx();

        foreach ($criteria as $field => $value) {
            $andX->add(new Expr\Comparison("u.$field", '=', ":$field"));
        }

        return $andX;
    }
}
