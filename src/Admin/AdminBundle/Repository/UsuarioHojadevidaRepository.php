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
    public function getUsrIdHojadevida($iduser){
        $result=$this->createQueryBuilder('uh')
            ->select('uh','h')
            ->join('uh.idHojadevida','h')
            ->where('uh.idUsuario =:id')
            ->setParameter('id',$iduser)
            ->getQuery();
        try{
            if($result->getOneOrNullResult())
            {
                return $result->getOneOrNullResult()->getIdHojadevida();
            }else{
                return null;
            }
        }catch (\Exception $e){
            return null;
        }
    }

}
