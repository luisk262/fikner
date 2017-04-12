<?php

namespace Admin\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**

 *
 * @author Family
 */
class UserRepository extends EntityRepository {

    public function count() {        
        $count = $this->createQueryBuilder('c')
                    ->andWhere('c.roles  NOT LIKE :roles')
                    ->setParameter('roles', '%' . 'ROLE_AGENC' . '%')
                    ->getQuery();
        return $count->getArrayResult();
    }
    public function userrole($userid){
        $query=$this->getEntityManager()->createQuery('SELECT p.id,p.image FROM  AdminAdminBundle:UsuarioHojadevida uh 
INNER JOIN AdminAdminBundle:HojadevidaPhoto hp WITH hp.idHojadevida = uh.idHojadevida 
INNER JOIN AdminAdminBundle:User u WITH u.id= uh.idUsuario 
INNER JOIN AdminAdminBundle:Hojadevida h WITH h.id = uh.idHojadevida
INNER JOIN AdminAdminBundle:Photo p WITH p.id = hp.idPhoto
WHERE (hp.principal =1) AND (uh.idUsuario=:Usuario)')
        ->setParameter('Usuario',$userid);
        if ($query->getResult()) {
            $profile = $query->getResult();
            $profile = $query->setMaxResults(1)->getOneOrNullResult();
        }
        else{
            $profile=null;
        }
        return $profile;
    }

}
