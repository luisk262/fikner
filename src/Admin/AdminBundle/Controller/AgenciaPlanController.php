<?php

namespace Admin\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\AdminBundle\Entity\AgenciaPlan;
use Admin\AdminBundle\Form\AgenciaPlanType;

/**
 * AgenciaPlan controller.
 *
 * @Route("/dashboard/agenciaplan")
 */
class AgenciaPlanController extends Controller
{

    /**
     * Lists all AgenciaPlan entities.
     *
     * @Route("/", name="dashboard_agenciaplan")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminAdminBundle:AgenciaPlan')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new AgenciaPlan entity.
     *
     * @Route("/", name="dashboard_agenciaplan_create")
     * @Method("POST")
     * @Template("AdminAdminBundle:AgenciaPlan:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new AgenciaPlan();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('dashboard_agenciaplan_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a AgenciaPlan entity.
     *
     * @param AgenciaPlan $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(AgenciaPlan $entity)
    {
        $form = $this->createForm(new AgenciaPlanType(), $entity, array(
            'action' => $this->generateUrl('dashboard_agenciaplan_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new AgenciaPlan entity.
     *
     * @Route("/new", name="dashboard_agenciaplan_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new AgenciaPlan();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a AgenciaPlan entity.
     *
     * @Route("/{id}", name="dashboard_agenciaplan_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminAdminBundle:AgenciaPlan')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AgenciaPlan entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing AgenciaPlan entity.
     *
     * @Route("/{id}/edit", name="dashboard_agenciaplan_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminAdminBundle:AgenciaPlan')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AgenciaPlan entity.');
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
    * Creates a form to edit a AgenciaPlan entity.
    *
    * @param AgenciaPlan $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(AgenciaPlan $entity)
    {
        $form = $this->createForm(new AgenciaPlanType(), $entity, array(
            'action' => $this->generateUrl('dashboard_agenciaplan_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing AgenciaPlan entity.
     *
     * @Route("/{id}", name="dashboard_agenciaplan_update")
     * @Method("PUT")
     * @Template("AdminAdminBundle:AgenciaPlan:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminAdminBundle:AgenciaPlan')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AgenciaPlan entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('dashboard_agenciaplan_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a AgenciaPlan entity.
     *
     * @Route("/{id}", name="dashboard_agenciaplan_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminAdminBundle:AgenciaPlan')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find AgenciaPlan entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('dashboard_agenciaplan'));
    }

    /**
     * Creates a form to delete a AgenciaPlan entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('dashboard_agenciaplan_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
