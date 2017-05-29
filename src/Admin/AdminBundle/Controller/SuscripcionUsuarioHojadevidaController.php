<?php

namespace Admin\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\AdminBundle\Entity\SuscripcionUsuarioHojadevida;
use Admin\AdminBundle\Form\SuscripcionUsuarioHojadevidaType;
use DateTime;
use Symfony\Component\VarDumper\Tests\Fixture\DumbFoo;

/**
 * SuscripcionUsuarioHojadevida controller.
 *
 * @Route("/dashboard/suscripcionusuariohojadevida")
 */
class SuscripcionUsuarioHojadevidaController extends Controller
{

    /**
     * Lists all SuscripcionUsuarioHojadevida entities.
     *
     * @Route("/{id}/list", name="dashboard_suscripcionusuariohojadevida")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entities=$em->getRepository('AdminAdminBundle:SuscripcionUsuarioHojadevida')->findByIdHojadevida($id,array('Estado'=>'desc'));
        return array(
            'entities' => $entities,
            'idHojadevida'=>$id,
        );
    }
    /**
     * Creates a new SuscripcionUsuarioHojadevida entity.
     *
     * @Route("/", name="dashboard_suscripcionusuariohojadevida_create")
     * @Method("POST")
     * @Template("AdminAdminBundle:SuscripcionUsuarioHojadevida:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new SuscripcionUsuarioHojadevida();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $date = new DateTime('now', new \DateTimeZone('America/Bogota'));
            $entity->setFecha($date);
            $entity->setFechaupdate($date);
            $dias=intval($entity->getIdSuscripcion()->getDias())+1;
            $entity->setFechaVencimiento(date_add($date, date_interval_create_from_date_string($dias.' days')));
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('dashboard_suscripcionusuariohojadevida', array('id' => $entity->getIdHojadevida()->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a SuscripcionUsuarioHojadevida entity.
     *
     * @param SuscripcionUsuarioHojadevida $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SuscripcionUsuarioHojadevida $entity,$Hojadevida=null)
    {
        $entity->setIdHojadevida($Hojadevida);
        $form = $this->createForm(new SuscripcionUsuarioHojadevidaType(), $entity, array(
            'action' => $this->generateUrl('dashboard_suscripcionusuariohojadevida_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));
        return $form;
    }

    /**
     * Displays a form to create a new SuscripcionUsuarioHojadevida entity.
     *
     * @Route("/new/{id}", name="dashboard_suscripcionusuariohojadevida_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($id)
    {
        $entity = new SuscripcionUsuarioHojadevida();
        $em=$this->getDoctrine()->getManager();
        $hojadevida=$em->getRepository('AdminAdminBundle:Hojadevida')->find($id);
        $form   = $this->createCreateForm($entity,$hojadevida);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'id'=>$id
        );
    }
    /**
     * Displays a form to edit an existing SuscripcionUsuarioHojadevida entity.
     *
     * @Route("/{id}/edit", name="dashboard_suscripcionusuariohojadevida_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminAdminBundle:SuscripcionUsuarioHojadevida')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SuscripcionUsuarioHojadevida entity.');
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
    * Creates a form to edit a SuscripcionUsuarioHojadevida entity.
    *
    * @param SuscripcionUsuarioHojadevida $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(SuscripcionUsuarioHojadevida $entity)
    {
        $form = $this->createForm(new SuscripcionUsuarioHojadevidaType(), $entity, array(
            'action' => $this->generateUrl('dashboard_suscripcionusuariohojadevida_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->remove('idHojadevida');
        $form->remove('fechaupdate');
        $form->remove('fecha');

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing SuscripcionUsuarioHojadevida entity.
     *
     * @Route("/{id}", name="dashboard_suscripcionusuariohojadevida_update")
     * @Method("PUT")
     * @Template("AdminAdminBundle:SuscripcionUsuarioHojadevida:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminAdminBundle:SuscripcionUsuarioHojadevida')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SuscripcionUsuarioHojadevida entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('dashboard_suscripcionusuariohojadevida_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a SuscripcionUsuarioHojadevida entity.
     *
     * @Route("/{id}", name="dashboard_suscripcionusuariohojadevida_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminAdminBundle:SuscripcionUsuarioHojadevida')->findOneByIdHojadevida($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find SuscripcionUsuarioHojadevida entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('dashboard_suscripcionusuariohojadevida'));
    }

    /**
     * Creates a form to delete a SuscripcionUsuarioHojadevida entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('dashboard_suscripcionusuariohojadevida_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
