<?php

namespace Baazaar\BaazaarBundle\Entity\Repository;

use Baazaar\BaazaarBundle\Entity\Category;

/**
 * AdRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdRepository extends \Doctrine\ORM\EntityRepository
{
    public function getLatestAds($limit = null) {
         $qb = $this->createQueryBuilder('a')
                    ->select('a')
                    ->addOrderBy('a.createdAt', 'DESC');

        if(false === is_null($limit)) $qb->setMaxResults ($limit);

        return $qb->getQuery()->getResult();
    }

    public function getAdsByCategoryId($category_id) {

        $qb = $this->createQueryBuilder('a')
                    ->select('a')
                    ->join('a.categories', 'c')
                    ->where('c.id = :category_id')
                    ->orderBy('a.id', 'DESC')
                    ->setParameter(':category_id', $category_id);

        return $qb->getQuery()->getResult();
    }

    public function getAdsByUser($user_id) {
      $qb = $this->createQueryBuilder('a')
                  ->select('a')
                  ->join('a.owner', 'c')
                  ->where('c.id = :user_id')
                  ->orderBy('a.id', 'DESC')
                  ->setParameter(':user_id', $user_id);

      return $qb->getQuery()->getResult();
    }

}
