<?php

namespace Admin\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**

 *
 * @author Family
 */
class AgenciaRepository extends EntityRepository {

    public function count($state = '1') {
        $repository = $this->getEntityManager()
            ->getRepository('AdminAdminBundle:Agencia');
        $count = $repository->createQueryBuilder('a')
                //->select('COUNT(a)')
                ->andWhere('a.Activo =:estado')->setParameter('estado',$state)
                ->getQuery()
                ;
        //$count->getScalarResult();
        return $count->getArrayResult();
    }

}
