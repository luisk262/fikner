<?php

namespace Admin\AgenciaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\AdminBundle\Entity\Agencia;
use Admin\AdminBundle\Entity\AgenciaUsuario;
use Admin\AgenciaBundle\Form\AgenciaType;
use DateTime;

/**
 * Agencia controller.
 *
 * @Route("/Agencia/dashboard/Myagencia")
 */
class AgenciaController extends Controller {

    /**
     * Creates a new Agencia entity.
     *
     * @Route("/", name="agencia_dashboard_myagencia_create")
     * @Method("POST")
     * @Template("AdminAgenciaBundle:Agencia:new.html.twig")
     */
    public function createAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $entity = new Agencia();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $security_context = $this->get('security.context');
//definimos el usuario logeado
        $security_token = $security_context->getToken();
//definimos el usuario
        $user = $security_token->getUser();
        if ($form->isValid()) {
            $query = $em->createQuery(
                            'SELECT a
                        FROM AdminAdminBundle:Agencia a
                        WHERE (a.nit  =:nit )'
                    )->setParameter('nit', $entity->getNit());
            if ($query->getResult()) {
                $error = true;
                //agencia ya esta registrada
                return $this->render('AdminAgenciaBundle:Agencia:mensajerror.html.twig', array(
                            'nombre' => $entity->getNombreAgencia(),
                            'error' => $error,
                            'nit' => $entity->getNit()
                ));
            } else {
                $date = new DateTime('now', new \DateTimeZone('America/Bogota'));
                $entity->setNomsRepLegal($user->getNombre());
                $entity->setApellsRepLegal($user->getApellidos());
                $entity->setActivo('0');
                $entity->setPrivado('1');
                $user->addRole('ROLE_AGENC');
                $entity->setFecha($date);
                $entity->setFechaupdate($date);
                $em->persist($entity);
                $em->flush();
                $AgenciaUsuario = new AgenciaUsuario();
                $AgenciaUsuario->setIdUsuario($user);
                $AgenciaUsuario->setIdAgencia($entity);
                $AgenciaUsuario->setFecha($date);
                $AgenciaUsuario->setFechaupdate($date);
                $em->persist($user);
                $em->persist($AgenciaUsuario);
                $em->flush();
            }
            return $this->redirect($this->generateUrl('agencia_dashboard'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'AgenciaP' => null
        );
    }

    /**
     * Creates a form to create a Agencia entity.
     *
     * @param Agencia $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Agencia $entity) {
        $form = $this->createForm(new AgenciaType(), $entity, array(
            'action' => $this->generateUrl('agencia_dashboard_myagencia_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Agencia entity.
     *
     * @Route("/new", name="agencia_dashboard_myagencia_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        $entity = new Agencia();
        $form = $this->createCreateForm($entity);
        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'AgenciaP' => null
        );
    }

    /**
     * Finds and displays a Agencia entity.
     *
     * @Route("/show", name="agencia_dashboard_myagencia_show")
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
                        'SELECT au
                        FROM AdminAdminBundle:AgenciaUsuario au
                        WHERE au.idUsuario =:id'
                )->setParameter('id', $user->getId());
        if ($query->getResult()) {
            $AgenciaU = $query->getResult();
            // Buscamos el array de resultados
            $AgenciaU = $query->setMaxResults(1)->getOneOrNullResult();
            if ($AgenciaU) {
                $entity = $em->getRepository('AdminAdminBundle:Agencia')->find($AgenciaU->getIdAgencia()->getId());
                if (!$entity) {
                    return $this->redirect($this->generateUrl('agencia_dashboard'));
                }
                //verificamos si la agencia tiene logotipo
                $query = $em->createQuery(
                                'SELECT ap
                        FROM AdminAdminBundle:AgenciaPhoto ap
                        WHERE ap.idAgencia  =:id'
                        )->setParameter('id', $AgenciaU->getIdAgencia()->getId());
                if ($query->getResult()) {
                    //la agencia tiene logotipo
                    $Agenciaphoto = $query->getResult();
                    // Buscamos el array de resultados
                    $Agenciaphoto = $query->setMaxResults(1)->getOneOrNullResult();
                    $logoId = $Agenciaphoto->getIdPhoto()->getId();
                    $logoName = $Agenciaphoto->getIdPhoto()->getImage();
                } else {
                    $logoId = null;
                    $logoName = null;
                }
            } else {
                return $this->redirect($this->generateUrl('agencia_dashboard')); #
            }
        } else {
            return $this->redirect($this->generateUrl('agencia_dashboard')); #
        }

        return array(
            'entity' => $entity,
            'logoId' => $logoId,
            'logoName' => $logoName,
            'AgenciaP' => null
        );
    }

    /**
     * Displays a form to edit an existing Agencia entity.
     *
     * @Route("/edit/agencia", name="agencia_dashboard_myagencia_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction() {
        $em = $this->getDoctrine()->getManager();
        $security_context = $this->get('security.context');
        //definimos el usuario logeado
        $security_token = $security_context->getToken();
        //definimos el usuario, con rol diferentea cordinador, administrador,suberadmin,usuario
        $user = $security_token->getUser();
        $query = $em->createQuery(
                        'SELECT au
                        FROM AdminAdminBundle:AgenciaUsuario au
                        WHERE au.idUsuario =:id'
                )->setParameter('id', $user->getId());
        if ($query->getResult()) {
            $AgenciaU = $query->getResult();
            // Buscamos el array de resultados
            $AgenciaU = $query->setMaxResults(1)->getOneOrNullResult();
            $entity = $em->getRepository('AdminAdminBundle:Agencia')->find($AgenciaU->getIdAgencia()->getId());
            //verificamos el tipo de plan que tiene la agencia//
            $agenciaPlan =$em->getRepository('AdminAdminBundle:Agencia')->plan($AgenciaU->getIdAgencia()->getId());

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Agencia entity.');
            }

            $editForm = $this->createEditForm($entity);

            return array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
                'AgenciaP' => $agenciaPlan
            );
        } else {
            return $this->redirect($this->generateUrl('agencia_dashboard'));
        }
    }

    /**
     * Creates a form to edit a Agencia entity.
     *
     * @param Agencia $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Agencia $entity) {
        $form = $this->createForm(new AgenciaType(), $entity, array(
            'action' => $this->generateUrl('agencia_dashboard_myagencia_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->add('nit', 'text', array('disabled' => true,));
        $form->add('firmaemail','textarea', array('label' => 'Firma para email saliente','required' => false));
        $form->add('paginaweb', 'text', array('label' => 'Pagina web', 'required' => false));
        $form->add('grupofb', 'text', array('label' => 'Grupo FB', 'required' => false));
        $form->add('youtube', 'text', array('label' => 'Youtube', 'required' => false));
        $form->add('twitter', 'text', array('label' => 'Twitter', 'required' => false));
        $form->add('copiaemail', 'checkbox', array('label' => 'Enviar una copia al email', 'required' => false));
        //verificamos el tipo de plan que tiene la agencia//
        $em=$this->get('doctrine')->getEntityManager();
        $agenciaPlan =$em->getRepository('AdminAdminBundle:Agencia')->plan($entity->getId());
        $privacidad = null;
        if ($agenciaPlan) {
            foreach ($agenciaPlan as $aux) {
                if ($aux['codigo'] == '2004')
                    $privacidad = 1;
            }
        }
        if ($privacidad) {
            $form->add('privado', 'checkbox', array('label' => 'Privada', 'required' => false));
        }
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Agencia entity.
     *
     * @Route("/{id}", name="agencia_dashboard_myagencia_update")
     * @Method("PUT")
     * @Template("AdminAdminBundle:Agencia:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
                        'SELECT a
        FROM AdminAdminBundle:Agencia a
        WHERE a.id  =:id'
                )->setParameter('id', $id);

        $age = $query->getResult();
        // en esta instruccion sacamos los nombres de las imagenes que estan en la base de datos
        $age = $query->setMaxResults(1)->getOneOrNullResult();
        $entity = $em->getRepository('AdminAdminBundle:Agencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Agencia entity.');
        }

        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $video=str_replace('https://www.youtube.com/watch?v=','',$entity->getVideoPrincipal());
            $entity->setVideoPrincipal($video);
            $date = new DateTime('now', new \DateTimeZone('America/Bogota'));
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('agencia_dashboard_myagencia_show'));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'AgenciaP' => null
        );
    }
    /**
     * Finds and displays a Photo entity.
     *
     * @Route("/agencialogo/{id}/show", name="Agencia_logoedit_show")
     * @Method("GET")
     * @Template()
     */
    public function editlogoAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminAdminBundle:Photo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Photo entity.');
        }
        
        $deleteForm =  $this->createDeleteForm($id);
        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
            'AgenciaP' => null
        );
    }
     /**
     * Creates a form to delete a Photo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    public function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('Agencia_photo_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Eliminar','attr'=>array('class'=>'btn btn-danger btn-block')))
                        ->getForm()
        ;
    }

}
