<?php

namespace Admin\ReclutadorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends Controller
{
   /**
     * Displays a form to create a new Hojadevida entity.
     *
     * @Route("/Reclutador/msg", name="Reclutador_registration_msg")
     * @Method("GET")
     * @Template("AdminReclutadorBundle:views:mensaje.html.twig")
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
     * Displays a form to create a new Hojadevida entity.
     *
     * @Route("/reclutador/{id}", name="Reclutador_page")
     * @Method("GET")
     * @Template("AdminReclutadorBundle:views:page.html.twig")
     */
    public function ReclutadorPageAction($id) {
        return array(
            'id' => $id
        );
    }


}
