<?php

namespace Admin\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

class NotificacionController extends Controller {

    /**
     * @Route("/notificar/agencia", name="notificar_agencia")
     * @Method("GET")
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $agencias = $em->getRepository('AdminAdminBundle:Agencia')->findBy(array('Activo' => true));
        foreach ($agencias as $agencia) {
            $Inactivos = count($em->getRepository('AdminAdminBundle:AgenciaHojadevida')->notificarfind($agencia->getId()));
            if ($Inactivos > 0) {
                $data['titulo1'] = strtoupper($agencia->getNombreAgencia());
                $data['titulo2'] = 'Personas están interesadas en  ser parte de su agencia.';
                $data['body'] = 'Tiene ' . $Inactivos . ' perfiles inactivos por favor actívelos para disfrutar de los beneficios que fikner tiene para usted.';
                $data['firma'] = '';
                $template = $this->renderView('AdminAdminBundle:views:email_notificacion.html.twig', array('data' => $data));
                $message = \Swift_Message::newInstance()
                        ->setSubject('Fikner - Nuevos perfiles se han postulado en su agencia')
                        ->setFrom($this->getParameter('mail'))
                        ->setTo($agencia->getEmail())
                        ->setBody($template, 'text/html')
                ;
                $this->get('mailer')->send($message);
            }
        }
    return new Response('1');
    }

}
