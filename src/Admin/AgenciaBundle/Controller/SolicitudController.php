<?php

namespace Admin\AgenciaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\AgenciaBundle\Entity\Solicitud;
use Admin\AgenciaBundle\Form\SolicitudType;
use DateTime;

/**
 * Solicitud controller.
 *
 * @Route("/Agencia/dashboard/solicitud")
 */
class SolicitudController extends Controller {

    /**
     * Lists all Solicitud entities.
     *
     * @Route("/", name="Agencia_dashboard_solicitud")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $security_context = $this->get('security.token_storage');
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
            $AgenciaUsuario = $query->getResult();
            // Buscamos el array de resultados
            $AgenciaUsuario = $query->setMaxResults(1)->getOneOrNullResult();
            $agenciaPlan =$em->getRepository('AdminAdminBundle:Agencia')->plan($AgenciaUsuario->getIdAgencia()->getId());
            $entities = $em->getRepository('AdminAgenciaBundle:Solicitud')->findBy(array('idAgencia' => $AgenciaUsuario->getIdAgencia()->getId(), 'activo' => '1'));
        } else {
            $agenciaPlan = null;
            $entities = null;
        }


        return array(
            'entities' => $entities,
            'AgenciaP' => $agenciaPlan
        );
    }

    /**
     * Creates a new Solicitud entity.
     *
     * @Route("/", name="Agencia_dashboard_solicitud_create")
     * @Method("POST")
     * @Template("AdminAgenciaBundle:Solicitud:new.html.twig")
     */
    public function createAction(Request $request) {
        $entity = new Solicitud();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $agencia = $em->getRepository('AdminAdminBundle:AgenciaUsuario')->findBy(array('idUsuario' => $this->getUser()->getId()));
            $entity->setIdAgencia($agencia[0]->getIdAgencia());
            $entity->setIdUsuario($this->getUser());
            $entity->setActivo(true);
            $entity->setFecha(new \DateTime('now'));
            $entity->setFechaupdate(new \DateTime('now'));
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('Agencia_dashboard_solicitud_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Solicitud entity.
     *
     * @param Solicitud $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Solicitud $entity) {
        $form = $this->createForm(new SolicitudType(), $entity, array(
            'action' => $this->generateUrl('Agencia_dashboard_solicitud_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear'));

        return $form;
    }

    /**
     * Displays a form to create a new Solicitud entity.
     *
     * @Route("/new", name="Agencia_dashboard_solicitud_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        $entity = new Solicitud();
        $form = $this->createCreateForm($entity);
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
                        'SELECT au
                        FROM AdminAdminBundle:AgenciaUsuario au
                        WHERE au.idUsuario  =:id'
                )->setParameter('id', $this->getUser()->getId());
        if ($query->getResult()) {
            $AgenciaUsuario = $query->getResult();
            // Buscamos el array de resultados
            $AgenciaUsuario = $query->setMaxResults(1)->getOneOrNullResult();
            $agenciaPlan =$em->getRepository('AdminAdminBundle:Agencia')->plan($AgenciaUsuario->getIdAgencia()->getId());
            $entities = $em->getRepository('AdminAgenciaBundle:Solicitud')->findAll();
        } else {
            $agenciaPlan = null;
            $entities = null;
        }
        return array(
            'entity' => $entity,
            'AgenciaP' => $agenciaPlan,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Solicitud entity.
     *
     * @Route("/{id}", name="Agencia_dashboard_solicitud_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
                        'SELECT au
                        FROM AdminAdminBundle:AgenciaUsuario au
                        WHERE au.idUsuario  =:id'
                )->setParameter('id', $this->getUser()->getId());
        if ($query->getResult()) {
            $AgenciaUsuario = $query->getResult();
            // Buscamos el array de resultados
            $AgenciaUsuario = $query->setMaxResults(1)->getOneOrNullResult();
            $agenciaPlan =$em->getRepository('AdminAdminBundle:Agencia')->plan($AgenciaUsuario->getIdAgencia()->getId());
            $entity = $em->getRepository('AdminAgenciaBundle:Solicitud')->findBy(array('id' => $id, 'idAgencia' => $AgenciaUsuario->getIdAgencia()->getId()));

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Solicitud entity.');
            }
        } else {
            $agenciaPlan = null;
            $entities = null;
        }


        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity[0],
            'AgenciaP' => $agenciaPlan,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Solicitud entity.
     *
     * @Route("/{id}/edit", name="Agencia_dashboard_solicitud_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminAgenciaBundle:Solicitud')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Solicitud entity.');
        }
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
                        'SELECT au
                        FROM AdminAdminBundle:AgenciaUsuario au
                        WHERE au.idUsuario  =:id'
                )->setParameter('id', $this->getUser()->getId());
        if ($query->getResult()) {
            $AgenciaUsuario = $query->getResult();
            // Buscamos el array de resultados
            $AgenciaUsuario = $query->setMaxResults(1)->getOneOrNullResult();
            $agenciaPlan =$em->getRepository('AdminAdminBundle:Agencia')->plan($AgenciaUsuario->getIdAgencia()->getId());
            $entities = $em->getRepository('AdminAgenciaBundle:Solicitud')->findAll();
        } else {
            $agenciaPlan = null;
            $entities = null;
        }
        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'AgenciaP' => $agenciaPlan,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a Solicitud entity.
     *
     * @param Solicitud $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Solicitud $entity) {
        $form = $this->createForm(new SolicitudType(), $entity, array(
            'action' => $this->generateUrl('Agencia_dashboard_solicitud_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }

    /**
     * Edits an existing Solicitud entity.
     *
     * @Route("/{id}", name="Agencia_dashboard_solicitud_update")
     * @Method("PUT")
     * @Template("AdminAgenciaBundle:Solicitud:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminAgenciaBundle:Solicitud')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Solicitud entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('Agencia_dashboard_solicitud_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Solicitud entity.
     *
     * @Route("/{id}", name="Agencia_dashboard_solicitud_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminAgenciaBundle:Solicitud')->find($id);
            $date = new DateTime('now', new \DateTimeZone('America/Bogota'));
            $entity->setActivo('0');
            $entity->setFechaupdate($date);
            $em->persist($entity);
            $em->flush();
        }
         return $this->redirect($this->generateUrl('Agencia_dashboard_solicitud'));
    }

    /**
     * Creates a form to delete a Solicitud entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('Agencia_dashboard_solicitud_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Eliminar', 'attr' => array('class' => 'btn btn-danger btn-block')))
                        ->getForm()
        ;
    }

}
