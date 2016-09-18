<?php

namespace Admin\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\AdminBundle\Entity\AgenciaHojadevida;
use Admin\AdminBundle\Form\AgenciaHojadevidaType;

/**
 * AgenciaHojadevida controller.
 *
 * @Route("/agenciahojadevida")
 */
class AgenciaHojadevidaController extends Controller
{

    /**
     * Lists all AgenciaHojadevida entities.
     *
     * @Route("/", name="agenciahojadevida")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminAdminBundle:AgenciaHojadevida')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new AgenciaHojadevida entity.
     *
     * @Route("/", name="agenciahojadevida_create")
     * @Method("POST")
     * @Template("AdminAdminBundle:AgenciaHojadevida:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new AgenciaHojadevida();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('agenciahojadevida_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a AgenciaHojadevida entity.
     *
     * @param AgenciaHojadevida $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(AgenciaHojadevida $entity)
    {
        $form = $this->createForm(new AgenciaHojadevidaType(), $entity, array(
            'action' => $this->generateUrl('agenciahojadevida_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new AgenciaHojadevida entity.
     *
     * @Route("/new", name="agenciahojadevida_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new AgenciaHojadevida();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a AgenciaHojadevida entity.
     *
     * @Route("/{id}", name="agenciahojadevida_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminAdminBundle:AgenciaHojadevida')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AgenciaHojadevida entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing AgenciaHojadevida entity.
     *
     * @Route("/{id}/edit", name="agenciahojadevida_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminAdminBundle:AgenciaHojadevida')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AgenciaHojadevida entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a AgenciaHojadevida entity.
    *
    * @param AgenciaHojadevida $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(AgenciaHojadevida $entity)
    {
        $form = $this->createForm(new AgenciaHojadevidaType(), $entity, array(
            'action' => $this->generateUrl('agenciahojadevida_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing AgenciaHojadevida entity.
     *
     * @Route("/{id}", name="agenciahojadevida_update")
     * @Method("PUT")
     * @Template("AdminAdminBundle:AgenciaHojadevida:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminAdminBundle:AgenciaHojadevida')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AgenciaHojadevida entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('agenciahojadevida_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a AgenciaHojadevida entity.
     *
     * @Route("/{id}", name="agenciahojadevida_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminAdminBundle:AgenciaHojadevida')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find AgenciaHojadevida entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('agenciahojadevida'));
    }

    /**
     * Creates a form to delete a AgenciaHojadevida entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('agenciahojadevida_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
