<?php

namespace Admin\MyaccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller {

    /**
     * Displays a form to create a new Hojadevida entity.
     *
     * @Route("/msg", name="registration_msg")
     * @Method("GET")
     * @Template("AdminMyaccountBundle:views:mensaje.html.twig")
     */
    public function mensajeAction() {

        $request = $this->getRequest();
        $errormsg = '';
        $nombre = $request->query->get('nombre');
        $apellidos = $request->query->get('apellidos');
        $idAgencia = $request->query->get('idAgencia');
        $idReclutador = $request->query->get('idReclutador');
        $error = $request->query->get('error');
        $errormsg = $request->query->get('errormsg');
        $id = $request->query->get('id');
//Asignamos el parametro 
        return array(
            'nombre' => $nombre,
            'apellidos' => $apellidos,
            'idAgencia' => $idAgencia,
            'idReclutador' => $idReclutador,
            'error' => $error,
            'id' => $id,
            'errormsg' => $errormsg);
    }

    /**
     * Displays a form to create a new Hojadevida entity.
     *
     * @Route("/terminos", name="registration_terminos")
     * @Method("GET")
     * @Template("AdminMyaccountBundle:views:terminos.html.twig")
     */
    public function terminosAction() {
        
    }

    /**
     * Displays a form to create a new Hojadevida entity.
     *
     * @Route("/calificacion", name="Myaccount_calificacion")
     * @Method("GET")
     * @Template("AdminMyaccountBundle:views:calificacion.html.twig")
     */
    public function calificacionAction() {
        
    }

    /**
     * Displays a form to create a new Hojadevida entity.
     *
     * @Route("/book/{id}", name="list_book")
     * @Method("GET")
     * @Template("AdminMyaccountBundle:views:book.html.twig")
     */
    public function bookAction($id) {
        $em = $this->getDoctrine()->getManager();
        $queryaux = $em->createQueryBuilder()
                ->select('COUNT(HP)')
                ->from('AdminAdminBundle:HojadevidaPhoto', 'HP')
                ->andWhere('HP.idHojadevida =:idHojadevida')
                ->setParameter('idHojadevida', $id);
        $total_Photos = $queryaux->getQuery()->getSingleScalarResult();
        if ($total_Photos > 0) {
            $photos = true;
            $query = $em->createQuery(
                            'SELECT hp
                        FROM AdminAdminBundle:HojadevidaPhoto hp
                        WHERE (hp.idHojadevida  =:id)'
                    )->setParameter('id', $id);
            if ($query->getResult()) {
                $HojadevidaP = $query->getResult();
                foreach ($HojadevidaP as $aux) {
                    $ids[] = $aux->getIdPhoto(); //convertimos la consulta en un array
                }
            } else {
                $HojadevidaP = null;
                $ids[] = null;
            }
            $entryQuery = $em->createQueryBuilder()
                    ->select('P')
                    ->from('AdminAdminBundle:Photo', 'P')
                    ->andWhere('P.id in (:ids)')
                    ->addOrderBy('P.fechaupdate', 'DESC')
                    ->setParameter('ids', $ids);
            $entryQueryfinal = $entryQuery->getQuery();
            //obtenemos el array de resultados
            $entities = $entryQueryfinal->getArrayResult();
        } else {
            $photos = false;
            $entities = null;
            $HojadevidaP=null;
        }

        return array(
            'id' => $id,
            'photos' => $photos,
            'entities' => $entities,
            'HojadevidaP' => $HojadevidaP,
        );
    }

    /**
     * recibe y envia el problema a el email del desarrollador del sistema
     *
     * @Route("/Myaccount/reportar/problema", name="Myaccount_reportarproblema")
     * @Method("GET")
     * @Template("AdminMyaccountBundle:views:reportarproblemas.html.twig")
     */
    public function reportarproblemasAction() {
        $security_context = $this->get('security.context');
        $security_token = $security_context->getToken();
        //definimos el usuario, con rol diferentea cordinador, administrador,suberadmin,usuario
        $user = $security_token->getUser();
        $aux = AccountController::profile();
        if(!$aux){
            $Image=null;
            $idfoto=null;
        }
        else{
            $Image=$aux[0]['image'];
            $idfoto=$aux[0]['id'];
        }
        return array(
            'Image' => $Image,
            'idfoto' => $idfoto,
            'user' => $user);
    }

    /**
     * envia el problema a el email del desarrollador del sistema
     *
     * @Route("/Myaccount/reportar/problema/send", name="Myaccount_enviarproblema")
     * @Method("GET")
     * @Template("AdminMyaccountBundle:views:sendproblemas.html.twig")
     */
    public function sendproblemaAction(Request $request) {
        $security_context = $this->get('security.context');
        $security_token = $security_context->getToken();
        //definimos el usuario, con rol diferentea cordinador, administrador,suberadmin,usuario
        $user = $security_token->getUser();
        ///procedemos a enviar el email
        $asunto = $request->query->get('asunto');
        $Body = $request->query->get('mensaje');
        $correo_remitente = 'youfikner@gmail.com';
        $Subject = 'Fikner - ' . $user->getNombre() . ':' . $asunto;
        $emailuser = $user->getEmail();
        $email = 'luisk__@hotmail.com';
        $message = \Swift_Message::newInstance()
                ->setSubject($Subject)
                ->setFrom($correo_remitente)
                ->setTo($email)
                ->setBody(
                <<<EOF
                Asunto:  $Subject
                Correo:  $email
                
                
                        $Body               
                
                Responda este email a $emailuser
                
EOF
                )
        ;
        $this->get('mailer')->send($message);
        $aux = AccountController::profile();
        return array(
            'Image' => $aux['image'],
            'idfoto' => $aux['id']);
    }

}
