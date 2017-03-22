<?php

namespace Admin\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;
/**
 * seguimientoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AgenciaHojadevidaRepository extends EntityRepository
{
 public function notificarfind($idAgencia){
      $result = $this->createQueryBuilder('n')
              ->andWhere('n.Estado =:Estado')->setParameter('Estado','Inactivo')
              ->andWhere('n.Activo =:activo')->setParameter('activo',true)
              ->andWhere('n.idAgencia =:agencia')->setParameter('agencia',$idAgencia)
                ->getQuery()
                ;
      return $result->getArrayResult();
 }    
}