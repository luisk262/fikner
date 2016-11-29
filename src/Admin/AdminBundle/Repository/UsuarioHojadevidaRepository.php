<?php

namespace Admin\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**

 *
 * @author Family
 */
class UsuarioHojadevidaRepository extends EntityRepository {

    public function count() {
       $count = $this->createQueryBuilder('uh')
                ->getQuery();
        //$count->getScalarResult();
        return $count->getArrayResult();
    }

}
