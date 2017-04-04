<?php

namespace Admin\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**

 *
 * @author Family
 */
class AgenciaRepository extends EntityRepository {

    public function count($state = '1') {
        $count = $this->createQueryBuilder('a')
                //->select('COUNT(a)')
                ->andWhere('a.Activo =:estado')->setParameter('estado',$state)
                ->getQuery()
                ;
        //$count->getScalarResult();
        return $count->getArrayResult();
    }
    public function plan($idAgencia){
        $em = $this->getEntityManager();
       $query=$em->createQuery(
            'SELECT a.id,a.nombre_agencia,p.nombre, ap.fechaCreacion,s.codigo FROM  AdminAdminBundle:Agencia a 
INNER JOIN AdminAdminBundle:AgenciaPlan ap WITH a.id= ap.idAgencia 
INNER JOIN AdminAdminBundle:Plan p WITH p.id= ap.idPlan 
INNER JOIN AdminAdminBundle:PlanServicio ps WITH ps.idPlan =p.id
INNER JOIN AdminAdminBundle:Servicio s WITH s.id = ps.idServicio
WHERE a.id=:id'
        )->setParameter('id', $idAgencia);
        if ($query->getResult()) {
            $Agenciaplan = $query->getResult();
        } else {
            $Agenciaplan = null;
        }
        return $Agenciaplan;
    }

}
