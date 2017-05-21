<?php

namespace Admin\MyaccountBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\AdminBundle\Entity\Hojadevida;
use Admin\MyaccountBundle\Form\PerfilType;
use DateTime;
use Admin\AdminBundle\Entity\UsuarioHojadevida;
use Admin\AdminBundle\Entity\AgenciaHojadevida;

/**
 * Catalogo controller.
 *
 * @Route("Myaccount/perfil")
 */
class PerfilController extends Controller {

    /**
     * Displays a form to create a new Hojadevida entity.
     *
     * @Route("/new", name="Myaccount_perfil_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        $request = $this->getRequest();
        //Asignamos el parametro url para luego pasarlo a ajax
        $idAgencia = $request->query->get('idAgencia');
        $idReclutador = $request->query->get('idReclutador');
        $entity = new Hojadevida();
        $form = $this->createCreateForm($entity, $idAgencia,$idReclutador);
        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'Image' => null,
            'idAgencia' => $idAgencia,
            'idReclutador' => $idReclutador,
        );
    }

    /**
     * Creates a form to create a Hojadevida entity.
     *
     * @param registro $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Hojadevida $entity, $idAgencia,$idReclutador) {
        ///procedemos a buscar el numero celular del usuario 
        $security_context = $this->get('security.context');
//definimos el usuario logeado
        $security_token = $security_context->getToken();
//definimos el usuario, con rol diferentea cordinador, administrador,suberadmin,usuario
        $user = $security_token->getUser();
        $form = $this->createForm(new PerfilType(), $entity, array(
            'action' => $this->generateUrl('Myaccount_perfil_create', array('idAgencia' => $idAgencia,'idReclutador'=>$idReclutador)),
            'method' => 'POST',
        ));
        $form->add('telCe', 'text', array('max_length' => 10, 'label' => 'Telefono Celular *', 'required' => true, 'data' => $user->getTelefono()));
        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Creates a new Hojadevida entity.
     *
     * @Route("/", name="Myaccount_perfil_create")
     * @Method("POST")
     * @Template("AdminMyaccountBundle:Perfil:new.html.twig")
     */
    public function createAction(Request $request) {
        $idAgencia = $request->query->get('idAgencia');
        $idReclutador=$request->query->get('idReclutador');
        $entity = new Hojadevida();
        $form = $this->createCreateForm($entity, $idAgencia,$idReclutador);
        $form->handleRequest($request);
        $security_context = $this->get('security.context');
//definimos el usuario logeado
        $security_token = $security_context->getToken();
//definimos el usuario, con rol diferentea cordinador, administrador,suberadmin,usuario
        $user = $security_token->getUser();

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                            'SELECT h
                        FROM AdminAdminBundle:Hojadevida h
                        WHERE (h.nit  =:nit )and (h.tipoDocumento=:tipod)'
                    )->setParameter('nit', $entity->getNit())
                    ->setParameter('tipod', $entity->getTipoDocumento());
            if ($query->getResult()) {
                $error = true;
                return $this->render('AdminMyaccountBundle:Perfil:mensajerror.html.twig', array(
                            'nombre' => $entity->getNombre(),
                            'apellidos' => $entity->getApellido(),
                            'error' => $error,
                            'email' => $entity->getEmailPersonal(),
                            'Image' => null
                ));
            } else {
                $date = new DateTime('now', new \DateTimeZone('America/Bogota'));
                $entity->setNombre($user->getNombre());
                $entity->setApellido($user->getApellidos());
                $entity->setEmailPersonal($user->getEmail());
                $entity->setFechaNac($user->getFechaNaci());
                $entity->setFecha($date);
                $entity->setFechaupdate($date);
                $em->persist($entity);
                $em->flush();
                $UsuarioHojadevida = new UsuarioHojadevida();
                $UsuarioHojadevida->setIdUsuario($user);
                $UsuarioHojadevida->setIdHojadevida($entity);
                $em->persist($UsuarioHojadevida);
                $em->flush();
                if ($idAgencia) {
                    $agencia = $em->getRepository('AdminAdminBundle:Agencia')->find($idAgencia);
                    $AgenciaHojadevida = new AgenciaHojadevida();
                    $AgenciaHojadevida->setIdAgencia($agencia);
                    $AgenciaHojadevida->setIdHojadevida($entity);
                    $AgenciaHojadevida->setEstado("Inactivo");
                    $AgenciaHojadevida->setActivo('1');
                    $AgenciaHojadevida->setFecha($date);
                    $AgenciaHojadevida->setFechaupdate($date);
                    $em->persist($AgenciaHojadevida);
                    $em->flush();
                    ////procedemos a verificar si la agencia es privada
                    //si es privada regalamos un talento
                    if ($agencia->getActivo()) {
                        if ($agencia->getPrivado()) {
                            $query = $em->createQuery(
                                            'SELECT h.id
                        FROM AdminAdminBundle:Hojadevida h INNER JOIN AdminAdminBundle:HojadevidaPhoto hp WITH hp.idHojadevida =h.id
                        WHERE (not EXISTS(SELECT ah FROM AdminAdminBundle:AgenciaHojadevida ah WHERE h.id = ah.idHojadevida and ah.idAgencia=:idAgencia)) and hp.principal=1
                        ')->setParameter('idAgencia', $agencia->getId());
                            if ($agenciaHojasdevida = $query->getResult()) {
                                $agenciaHojasdevidaaux = $query->getResult();
                                $agenciaHojasdevidaaux = $query->setMaxResults(1)->getOneOrNullResult();
                                $Hojadevida = $em->getRepository('AdminAdminBundle:Hojadevida')->find($agenciaHojasdevidaaux);
                                ///ingresamos el nuevo book a la agencia
                                $AgenciaHojadevida = new AgenciaHojadevida();
                                $AgenciaHojadevida->setIdAgencia($agencia);
                                $AgenciaHojadevida->setIdHojadevida($Hojadevida);
                                $AgenciaHojadevida->setEstado("Inactivo");
                                $AgenciaHojadevida->setActivo('1');
                                $AgenciaHojadevida->setFecha($date);
                                $AgenciaHojadevida->setFechaupdate($date);
                                $em->persist($AgenciaHojadevida);
                                $em->flush();
                            } else {
                              //si no encuentra que ingresar se le deberia un book 
                            }
                        }
                    }
///
                }
                if($idReclutador){
                    $reclutador = $em->getRepository('AdminAdminBundle:User')->find($idReclutador);
                    if($reclutador){
                    $reclutadorHojadevida = new \Admin\AdminBundle\Entity\ReclutadorHojadevida();
                    $reclutadorHojadevida->setIdUsuario($reclutador);
                    $reclutadorHojadevida->setIdHojadevida($entity);
                    $reclutadorHojadevida->setEstado('Pendiente');
                    $reclutadorHojadevida->setFecha($date);
                    $reclutadorHojadevida->setFechaupdate($date);
                    $em->persist($reclutadorHojadevida);
                    $em->flush();
                    }
                    
                }
            }
            return $this->redirect($this->generateUrl('Myaccount'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Hojadevida entity.
     *
     * @Route("/", name="Myaccount_perfil_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction() {
        $em = $this->getDoctrine()->getManager();
        $security_context = $this->get('security.context');
//definimos el usuario logeado
        $security_token = $security_context->getToken();
//definimos el usuario, con rol diferentea cordinador, administrador,suberadmin,usuario
        $user = $security_token->getUser();
        $query = $em->createQuery(
                        'SELECT uh
                        FROM AdminAdminBundle:UsuarioHojadevida uh
                        WHERE uh.idUsuario  =:id'
                )->setParameter('id', $user->getId());
        if ($query->getResult()) {
            $Hojadevida = $query->getResult();
// Buscamos el array de resultados
            $Hojadevida = $query->setMaxResults(1)->getOneOrNullResult();
            if ($Hojadevida) {
                $entity = $em->getRepository('AdminAdminBundle:Hojadevida')->find($Hojadevida->getIdHojadevida()->getId());
                ///sacamos imagen para mostrar
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
                if (!$entity) {
                    return $this->redirect($this->generateUrl('Myaccount_perfil_new'));
                }
            } else {
                return $this->redirect($this->generateUrl('Myaccount_perfil_new'));
            }
        } else {
            return $this->redirect($this->generateUrl('Myaccount_perfil_new'));
        }
        $vistaagencia=count($em->getRepository('AdminAdminBundle:SeguimientoAgencia')->findByidHojadevida($Hojadevida->getIdHojadevida()->getId()));
        $vistapublico=count($em->getRepository('AdminAdminBundle:SeguimientoBook')->findByidHojadevida($Hojadevida->getIdHojadevida()->getId()));
        return array(
            'entity' => $entity,
            'idfoto' => $idfoto,
            'Image' => $Image,
            'vistaagencias'=>$vistaagencia,
            'vistapublico'=>$vistapublico,
        );
    }

    /**
     * Displays a form to edit an existing Hojadevida entity.
     *
     * @Route("/edit", name="Myaccount_perfil_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction() {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $entity=$em->getRepository('AdminAdminBundle:UsuarioHojadevida')->getUsrIdHojadevida($user->getId());
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Hojadevida entity.');
        }
        $editForm = $this->createEditForm($entity);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView()
            //'idfoto' => $idfoto,
            //'Image' => $Image
        );
    }

    /**
     * Creates a form to edit a Hojadevida entity.
     *
     * @param Hojadevida $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Hojadevida $entity) {
        $form = $this->createForm(new PerfilType(), $entity, array(
            'action' => $this->generateUrl('Myaccount_perfil_update'),
            'method' => 'PUT',
        ));
        $form->add('tipoDocumento', 'choice', array(
            'choices' => array(
                'CC' => 'CC',
                'TI' => 'TI',
                'PAS' => 'PAS',
                'CE' => 'CE'
            ),
            'disabled' => true,
            'empty_value' => 'Seleccione tipo',
            'empty_data' => null
        ));
        $form->add('fechaNac', 'date', array(
            'years' => range(date('Y') - 95, date('Y')),
            'disabled' => true,
            'label' => 'Fecha de nacimimiento',
            'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
        ));
        $form->add('sexo', 'choice', array(
            'choices' => array(
                'Masculino' => 'Masculino',
                'Femenino' => 'Femenino',
            ),
            'disabled' => true,
            'label' => 'Sexo*',
            'empty_value' => 'Seleccione tipo',
            'empty_data' => null
        ));

        $form->add('nit', 'text', array('read_only' => true));
        $form->add('submit', 'submit', array('label' => 'Actualizar'));
        return $form;
    }

    /**
     * Edits an existing Hojadevida entity.
     *
     * @Route("/", name="Myaccount_perfil_update")
     * @Method("PUT")
     * @Template("AdminMyaccountBundle:Perfil:edit.html.twig")
     */
    public function updateAction(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $entity=$em->getRepository('AdminAdminBundle:UsuarioHojadevida')->getUsrIdHojadevida($user->getId());
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Hojadevida entity.');
        }
        $editForm = $this->createEditForm($entity);
        $cambios=array();
        $auxH=$request->request->get('admin_adminbundle_hojadevida');
        if($auxH['experienciaTv']!=$entity->getExperienciaTv()){
            $cambios['experienciaTv']='* Experiencia: '.$auxH['experienciaTv'].' http://fikner.com/Agencia/dashboard/hojadevida/'.$entity->getId();
        }
        $editForm->handleRequest($request);
        if ($editForm->isValid()) {
            $date = new DateTime('now', new \DateTimeZone('America/Bogota'));
            $entity->setFechaupdate($date);
            $em->persist($entity);
            $em->flush();
            if(isset($cambios['experienciaTv'])){
                $body= $cambios['experienciaTv'];
                $agenciasHs=$em->getRepository('AdminAdminBundle:AgenciaHojadevida')->findBy(array('idHojadevida'=>$entity->getId(),'Activo'=>true));
                $correo_remitente = 'youfikner@gmail.com';
                $Subject=$entity->getNombre().'- Realizo cambios en su perfil. ';
                $data['titulo1']='Tenemos una nueva noticia';
                $data['titulo2']=$Subject;
                $data['body']=$body;
                $data['firma']='';
                $template=$this->renderView('AdminAdminBundle:views:email.html.twig',array('data'=>$data));
                foreach ( $agenciasHs as $ah){
                    try{
                        $email = $ah->getIdAgencia()->getEmail();;
                        $message = \Swift_Message::newInstance()
                            ->setSubject($Subject)
                            ->setFrom($correo_remitente)
                            ->setTo($email)
                            ->setBody($template,'text/html')
                        ;
                        $this->get('mailer')->send($message);
                    }catch (\Exception $e){

                    }

                }
            }

            return $this->redirect($this->generateUrl('Myaccount_perfil_show'));
        }
        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        );
    }

    /**
     * Finds and displays a Hojadevida entity.
     *
     * @Route("/fullimage/{nit}/{id}/{foto}", name="Myaccount_perfil_showfoto")
     * @Method("GET")
     * @Template()
     */
    public function showfotoAction($nit, $id, $foto) {
        //mostrarmos la foto origunal
        $request = $this->getRequest();
        return array(
            'foto' => $foto,
            'nit' => $nit,
            'id' => $id
        );
    }

}
