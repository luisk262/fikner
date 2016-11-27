<?php

namespace Admin\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**

 *
 * @author Family
 */
class UserRepository extends EntityRepository {

    public function count() {
        $repository = $this->getEntityManager()
            ->getRepository('AdminAdminBundle:User');
        $count = $repository->createQueryBuilder('c')
                    ->andWhere('c.roles  NOT LIKE :roles')
                    ->setParameter('roles', '%' . 'ROLE_AGENC' . '%')
                    ->getQuery();
        return $count->getArrayResult();
    }

}
