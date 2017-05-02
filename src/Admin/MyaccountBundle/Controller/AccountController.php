<?php

namespace Admin\MyaccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


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
    public function indexAction(Request $request) {
        //Verificamos que usuario corresponda a la credencial.
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            return $this->redirect($this->generateUrl('admin_dashboard'));
        }
        else if ($this->get('security.authorization_checker')->isGranted('ROLE_AGENC')){
            return $this->redirect($this->generateUrl('agencia_dashboard'));
        }
        else if(($this->get('security.authorization_checker')->isGranted('ROLE_RECLU'))){
            return $this->redirect($this->generateUrl('reclutador_dashboard'));
        }
        $em = $this->getDoctrine()->getManager();
        $idUser=$this->get('security.token_storage')->getToken()->getUser()->getId();
        //Verificamos si el usuario ya completo los datos de registro de la hoja de vida
        $idhojadevida=$em->getRepository('AdminAdminBundle:UsuarioHojadevida')->findOneBy(array('idUsuario'=>$idUser));
        if($idhojadevida){
            $hojadevida = true;
            //Verificamos si el usuario ya cargo las imagenes
            $idImages=$em->getRepository('AdminAdminBundle:HojadevidaPhoto')->findOneBy(array('idHojadevida'=>$idhojadevida->getIdHojadevida()));
            if($idImages){
                $images = true;
                //Verificamos las agencias a las cuales se ha postulado
                $agencias = $em->getRepository('AdminAdminBundle:AgenciaHojadevida')->findBy(array('idHojadevida'=>$idhojadevida->getIdhojadevida(),'Activo'=>true));
                $vistasA = $em->getRepository('AdminAdminBundle:SeguimientoAgencia')->countHojadevida($idhojadevida->getIdhojadevida());
                $vistasB = $em->getRepository('AdminAdminBundle:SeguimientoBook')->countHojadevida($idhojadevida->getIdhojadevida());
                $vistas=$vistasA+$vistasB;
            }
            else{
                $images = false;
                $agencias=null;
                $vistas=0;
            }
        }
        else{
            $hojadevida = false;
            $agencias=null;
            $images=null;
            $vistas=0;
        }
        return array(
            'hojadevida' => $hojadevida,
            'photo' => $images,
            'entity'=>$agencias,
            'vistas'=>$vistas
        );


   //     }
    }
    /**
     * @Route("/Verificarusuario", name="Myaccount_verificarusuario")
     * @Template()
     */
    public function AgenciaAction(){
        $em= $this->getDoctrine()->getManager();
        $aux=$em->getRepository('AdminAdminBundle:User')->userrole($user->getId());
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
}
