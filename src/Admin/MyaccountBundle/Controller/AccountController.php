<?php

namespace Admin\MyaccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;



/**
 * User controller.
 *
 * @Route("/Myaccount")
 */
class AccountController extends Controller {

    /**
     * @Route("/", name="Myaccount")
     * @Template()
     */
    public function indexAction() {
        $request = $this->getRequest();
        //Asignamos el parametro url para luego pasarlo a ajax
        $idAgencia = $request->query->get('idAgencia');
        $idReclutador = $request->query->get('idReclutador');
        
        $security_context = $this->get('security.context');
        if ($this->get('security.context')->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->redirect($this->generateUrl('admin_dashboard'));
        }
        else if ($this->get('security.context')->isGranted('ROLE_AGENC')){
            return $this->redirect($this->generateUrl('agencia_dashboard'));            
        }
        else if(($this->get('security.context')->isGranted('ROLE_RECLU'))){
             return $this->redirect($this->generateUrl('reclutador_dashboard'));
        }
        $security_token = $security_context->getToken();
        //definimos el usuario, con rol diferentea cordinador, administrador,suberadmin,usuario
        $user = $security_token->getUser();
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
                        'SELECT uh
                        FROM AdminAdminBundle:UsuarioHojadevida uh
                        WHERE uh.idUsuario  =:id'
                )->setParameter('id', $user->getId());
        if ($query->getResult()) {
            $Hojadevida = $query->getResult();
            // Buscamos el array de resultados
            $Hojadevida = $query->setMaxResults(1)->getOneOrNullResult();
            $query = $em->createQuery(
                            'SELECT hp
                        FROM AdminAdminBundle:HojadevidaPhoto hp
                        WHERE hp.idHojadevida  =:id'
                    )->setParameter('id', $Hojadevida->getIdHojadevida()->getId());
            if ($query->getResult()) {
                $query = $em->createQuery(
                                'SELECT hp
                        FROM AdminAdminBundle:HojadevidaPhoto hp
                        WHERE (hp.idHojadevida  =:id) AND (hp.principal =:Valor)'
                        )->setParameter('id', $Hojadevida->getIdHojadevida()->getId())
                        ->setParameter('Valor', 1);
                if ($query->getResult()) {
                    $HojadevidaP = $query->getResult();
                    // Buscamos el array de resultados
                    $HojadevidaP = $query->setMaxResults(1)->getOneOrNullResult();
                    $idfoto = $HojadevidaP->getIdPhoto()->getId();
                    $Image = $HojadevidaP->getIdPhoto()->getImage();
                } else {
                    $idfoto = null;
                    $Image = null;
                }
                $estatura = $Hojadevida->getIdHojadevida()->getEstatura();
                $fechaNac = $Hojadevida->getIdHojadevida()->getFechaNac();
                $TelCe = $Hojadevida->getIdHojadevida()->getTelCe();
                $idbook = $Hojadevida->getIdHojadevida()->getId();
                $calificacion=$Hojadevida->getIdHojadevida()->getCalificacion();
                return array(
                    'hojadevida' => true,
                    'photo' => true,
                    'estatura' => $estatura,
                    'fechaNac' => $fechaNac,
                    'TelCe' => $TelCe,
                    'Image' => $Image,
                    'idfoto' => $idfoto,
                    'idbook'=>$idbook,
                    'calificacion'=>$calificacion,
                    'idAgencia'=>$idAgencia,
                    'idReclutador'=>$idReclutador,
                );
            } else {
                $estatura = $Hojadevida->getIdHojadevida()->getEstatura();
                $fechaNac = $Hojadevida->getIdHojadevida()->getFechaNac();
                $TelCe = $Hojadevida->getIdHojadevida()->getTelCe();
                $idbook = $Hojadevida->getIdHojadevida()->getId();
                return array(
                    'hojadevida' => true,
                    'photo' => false,
                    'estatura' => $estatura,
                    'fechaNac' => $fechaNac,
                    'TelCe' => $TelCe,
                    'Image' => null,
                    'idfoto' => null,
                    'calificacion'=>null,
                    'idAgencia'=>$idAgencia,
                    'idReclutador'=>$idReclutador,
                    'idbook'=>$idbook,
                );
            }
        } else {
            return array(
                'hojadevida' => false,
                'photo' => false,
                'estatura' => null,
                'fechaNac' => null,
                'TelCe' => null,
                'Image' => null,
                'idfoto' => null,
                'idbook'=>null,
                'calificacion'=>null,
                'idAgencia'=>$idAgencia,
                'idReclutador'=>$idReclutador,
            );
        }
    }
    /**
     * @Route("/Verificarusuario", name="Myaccount_verificarusuario")
     * @Template()
     */
    public function AgenciaAction(){
                $aux = AccountController::profile();
                 return array(
                'Image' =>$aux['image'],
                'idfoto' =>$aux['id'],
            );
                 
    }
     /**
     * @Route("/ChangeModalidad", name="Myaccount_ChangeModalidad")
     * @Template()
     */
    public function ChangeModalidadAction(){
         $security_context = $this->get('security.context');
        $security_token = $security_context->getToken();
        //definimos el usuario, con rol diferentea cordinador, administrador,suberadmin,usuario
        $em = $this->getDoctrine()->getManager();
        $user = $security_token->getUser();
        $user->removeRole('ROLE_USER');
        $user->addRole('ROLE_AGENC'); 
        $em->persist($user);
        $em->flush();
        return $this->redirect($this->generateUrl('fos_user_security_logout'));
    }
    public function profile(){
        $security_context = $this->get('security.context');
        $security_token = $security_context->getToken();
        //definimos el usuario, con rol diferentea cordinador, administrador,suberadmin,usuario
        $user = $security_token->getUser();
        $em = $this->getDoctrine()->getManager();
        $query =$em->createQuery(
                 'SELECT p.id,p.image FROM  AdminAdminBundle:UsuarioHojadevida uh 
INNER JOIN AdminAdminBundle:HojadevidaPhoto hp WITH hp.idHojadevida = uh.idHojadevida 
INNER JOIN AdminAdminBundle:User u WITH u.id= uh.idUsuario 
INNER JOIN AdminAdminBundle:Hojadevida h WITH h.id = uh.idHojadevida
INNER JOIN AdminAdminBundle:Photo p WITH p.id = hp.idPhoto
WHERE (hp.principal =1) AND (uh.idUsuario=:Usuario)
')->setParameter('Usuario',$user->getId());
        if ($query->getResult()) {
            $profile = $query->getResult();
        }
        else{
            $profile=null;
        }
        return $profile;
    }

   

}
