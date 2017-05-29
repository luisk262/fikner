<?php

namespace Admin\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\AdminBundle\Entity\SuscripcionUsuario;
use Admin\AdminBundle\Form\SuscripcionUsuarioType;

/**
 * SuscripcionUsuario controller.
 *
 * @Route("/dashboard/suscripcionusuario")
 */
class SuscripcionUsuarioController extends Controller
{

    /**
     * Lists all SuscripcionUsuario entities.
     *
     * @Route("/", name="dashboard_suscripcionusuario")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminAdminBundle:SuscripcionUsuario')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new SuscripcionUsuario entity.
     *
     * @Route("/", name="dashboard_suscripcionusuario_create")
     * @Method("POST")
     * @Template("AdminAdminBundle:SuscripcionUsuario:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new SuscripcionUsuario();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('dashboard_suscripcionusuario_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a SuscripcionUsuario entity.
     *
     * @param SuscripcionUsuario $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SuscripcionUsuario $entity)
    {
        $form = $this->createForm(new SuscripcionUsuarioType(), $entity, array(
            'action' => $this->generateUrl('dashboard_suscripcionusuario_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new SuscripcionUsuario entity.
     *
     * @Route("/new", name="dashboard_suscripcionusuario_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new SuscripcionUsuario();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a SuscripcionUsuario entity.
     *
     * @Route("/{id}", name="dashboard_suscripcionusuario_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminAdminBundle:SuscripcionUsuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SuscripcionUsuario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing SuscripcionUsuario entity.
     *
     * @Route("/{id}/edit", name="dashboard_suscripcionusuario_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminAdminBundle:SuscripcionUsuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SuscripcionUsuario entity.');
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
    * Creates a form to edit a SuscripcionUsuario entity.
    *
    * @param SuscripcionUsuario $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(SuscripcionUsuario $entity)
    {
        $form = $this->createForm(new SuscripcionUsuarioType(), $entity, array(
            'action' => $this->generateUrl('dashboard_suscripcionusuario_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing SuscripcionUsuario entity.
     *
     * @Route("/{id}", name="dashboard_suscripcionusuario_update")
     * @Method("PUT")
     * @Template("AdminAdminBundle:SuscripcionUsuario:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminAdminBundle:SuscripcionUsuario')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SuscripcionUsuario entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('dashboard_suscripcionusuario_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a SuscripcionUsuario entity.
     *
     * @Route("/{id}", name="dashboard_suscripcionusuario_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminAdminBundle:SuscripcionUsuario')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find SuscripcionUsuario entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('dashboard_suscripcionusuario'));
    }

    /**
     * Creates a form to delete a SuscripcionUsuario entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('dashboard_suscripcionusuario_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
