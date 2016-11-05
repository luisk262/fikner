<?php

namespace Admin\AgenciaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\AgenciaBundle\Entity\SolicitudHojadevida;
use Admin\AgenciaBundle\Form\SolicitudHojadevidaType;

/**
 * SolicitudHojadevida controller.
 *
 * @Route("/Agencia/dashboard/solicitudhojadevida")
 */
class SolicitudHojadevidaController extends Controller {

    /**
     * Lists all SolicitudHojadevida entities.
     *
     * @Route("/", name="Agencia_dashboard_solicitudhojadevida")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();
        $agencias = $em->getRepository('AdminAdminBundle:AgenciaUsuario')->findBy(array('idUsuario' => $this->getUser()->getId()));
        foreach ($agencias as $agencia) {
            
        }
        $solicitudes = $em->getRepository('AdminAgenciaBundle:Solicitud')->findall(array('idAgencia' => $agencia->getIdAgencia()->getId()));
        foreach ($solicitudes as $solicitud) {
            
        }
        $entities = $em->getRepository('AdminAgenciaBundle:SolicitudHojadevida')->findBy(array('idSolicitud' => $solicitud->getId()));
        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new SolicitudHojadevida entity.
     *
     * @Route("/", name="Agencia_dashboard_solicitudhojadevida_create")
     * @Method("POST")
     * @Template("AdminAgenciaBundle:SolicitudHojadevida:new.html.twig")
     */
    public function createAction(Request $request) {
        $entity = new SolicitudHojadevida();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('Agencia_dashboard_solicitudhojadevida_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a SolicitudHojadevida entity.
     *
     * @param SolicitudHojadevida $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SolicitudHojadevida $entity) {
        $form = $this->createForm(new SolicitudHojadevidaType(), $entity, array(
            'action' => $this->generateUrl('Agencia_dashboard_solicitudhojadevida_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new SolicitudHojadevida entity.
     *
     * @Route("/new", name="Agencia_dashboard_solicitudhojadevida_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        $entity = new SolicitudHojadevida();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a SolicitudHojadevida entity.
     *
     * @Route("/{id}", name="Agencia_dashboard_solicitudhojadevida_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminAgenciaBundle:SolicitudHojadevida')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SolicitudHojadevida entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing SolicitudHojadevida entity.
     *
     * @Route("/{id}/edit", name="Agencia_dashboard_solicitudhojadevida_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminAgenciaBundle:SolicitudHojadevida')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SolicitudHojadevida entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a SolicitudHojadevida entity.
     *
     * @param SolicitudHojadevida $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(SolicitudHojadevida $entity) {
        $form = $this->createForm(new SolicitudHojadevidaType(), $entity, array(
            'action' => $this->generateUrl('Agencia_dashboard_solicitudhojadevida_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing SolicitudHojadevida entity.
     *
     * @Route("/{id}", name="Agencia_dashboard_solicitudhojadevida_update")
     * @Method("PUT")
     * @Template("AdminAgenciaBundle:SolicitudHojadevida:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminAgenciaBundle:SolicitudHojadevida')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SolicitudHojadevida entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('Agencia_dashboard_solicitudhojadevida_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a SolicitudHojadevida entity.
     *
     * @Route("/{id}", name="Agencia_dashboard_solicitudhojadevida_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminAgenciaBundle:SolicitudHojadevida')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find SolicitudHojadevida entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('Agencia_dashboard_solicitudhojadevida'));
    }

    /**
     * Creates a form to delete a SolicitudHojadevida entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('Agencia_dashboard_solicitudhojadevida_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

}
