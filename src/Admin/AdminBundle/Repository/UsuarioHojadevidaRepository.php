<?php

namespace Admin\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**

 *
 * @author Family
 */
class UsuarioHojadevidaRepository extends EntityRepository {

    public function count() {
        $repository = $this->getEntityManager()
            ->getRepository('AdminAdminBundle:UsuarioHojadevida');
        $count = $repository->createQueryBuilder('uh')
                ->getQuery();
        //$count->getScalarResult();
        return $count->getArrayResult();
    }

}
