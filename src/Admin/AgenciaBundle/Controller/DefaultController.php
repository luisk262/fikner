<?php

namespace Admin\AgenciaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use DateTime;
use Admin\AdminBundle\Entity\AgenciaHojadevida;

class DefaultController extends Controller {

    /**
     * Displays a form to create a new Hojadevida entity.
     *
     * @Route("/Agencia/msg", name="Agencia_registration_msg")
     * @Method("GET")
     * @Template("AdminAgenciaBundle:views:mensaje.html.twig")
     */
    public function mensajeAction() {

        $request = $this->getRequest();
        $errormsg = '';
        $nombre = $request->query->get('nombre');
        $apellidos = $request->query->get('apellidos');
        $email = $request->query->get('email');
        $error = $request->query->get('error');
        $errormsg = $request->query->get('errormsg');
        $id = $request->query->get('id');
//Asignamos el parametro 
        return array(
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'email' => $email,
            'error' => $error,
            'id' => $id,
            'errormsg' => $errormsg);
    }
    /**
     * recibe y envia el problema a el email del desarrollador del sistema
     *
     * @Route("/Agencia/dashboard/reportar/problema", name="Agencia_reportarproblema")
     * @Method("GET")
     * @Template("AdminAgenciaBundle:views:reportarproblemas.html.twig")
     */
    public function reportarproblemasAction() {
        $security_context = $this->get('security.context');
        $security_token = $security_context->getToken();
        //definimos el usuario, con rol diferentea cordinador, administrador,suberadmin,usuario
        $user = $security_token->getUser();
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
                        'SELECT au
                        FROM AdminAdminBundle:AgenciaUsuario au
                        WHERE au.idUsuario  =:id'
                )->setParameter('id', $user->getId());
        if ($query->getResult()) {
            $Agencia = $query->getResult();
            // Buscamos el array de resultados
            $Agencia = $query->setMaxResults(1)->getOneOrNullResult();
//verificamos que plan tiene asignado la agencia
            $Agenciaplan = DashboardController::agenciaplan($Agencia->getIdAgencia()->getId());
        } else {
            $Agenciaplan = null;
            $Agencia = null;
        }
        return array('AgenciaP' => $Agenciaplan);
    }

    /**
     * envia el problema a el email del desarrollador del sistema
     *
     * @Route("/Agencia/dashboard/reportar/problema/send", name="Agencia_enviarproblema")
     * @Method("GET")
     * @Template("AdminAgenciaBundle:views:sendproblemas.html.twig")
     */
    public function sendproblemaAction(Request $request) {
        $security_context = $this->get('security.context');
        $security_token = $security_context->getToken();
        //definimos el usuario, con rol diferentea cordinador, administrador,suberadmin,usuario
        $user = $security_token->getUser();
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
                        'SELECT au
                        FROM AdminAdminBundle:AgenciaUsuario au
                        WHERE au.idUsuario  =:id'
                )->setParameter('id', $user->getId());
        if ($query->getResult()) {
            $Agencia = $query->getResult();
            // Buscamos el array de resultados
            $Agencia = $query->setMaxResults(1)->getOneOrNullResult();
//verificamos que plan tiene asignado la agencia
            $Agenciaplan = DashboardController::agenciaplan($Agencia->getIdAgencia()->getId());
        } else {
            $Agenciaplan = null;
            $Agencia = null;
        }
        ///procedemos a enviar el email
        $asunto = $request->query->get('asunto');

        $Body = $request->query->get('mensaje');
        $correo_remitente = 'youfikner@gmail.com';
        $Subject = 'Fikner - ' . $Agencia->getIdAgencia()->getNombreAgencia() . ':' . $asunto;
        $emailagencia = $Agencia->getIdAgencia()->getEmail();
        $nomagencia = $Agencia->getIdAgencia()->getNombreAgencia();
        $email = 'luisk__@hotmail.com';
        $message = \Swift_Message::newInstance()
                ->setSubject($Subject)
                ->setFrom($correo_remitente)
                ->setTo($email)
                ->setBody(
                <<<EOF
                Asunto:  $Subject
                Correo:  $email
                Agencia: $nomagencia
                
                        $Body               
                
                Responda este email a $emailagencia
                
EOF
                )
        ;
        $this->get('mailer')->send($message);
        return array('AgenciaP' => $Agenciaplan);
    }

    /**
     * @Route("/agencias/logo/{idAgencia}/{height}/{width}/view", name="agencia_logo_view")
     * @Method("GET")
     */
    public function AgenciaLogoViewAction($idAgencia, $height, $width) {
        $em = $this->getDoctrine()->getManager();
        $agencialogos = $em->getRepository('AdminAdminBundle:AgenciaPhoto')->findBy(array('idAgencia' => $idAgencia),null,1,null);
        if ($agencialogos) {
            $logo = $em->getRepository('AdminAdminBundle:Photo')->find($agencialogos[0]->getIdPhoto());
        } else {
            $logo = null;
        }
        return $this->render('AdminAgenciaBundle:views:logo.html.twig', array(
                    'entity' => $logo,
                    'height' => $height,
                    'width' => $width,
        ));
    }
    

}
