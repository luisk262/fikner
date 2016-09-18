<?php

namespace Admin\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\AdminBundle\Entity\Agencia;
use Admin\AdminBundle\Entity\User;
use Admin\AdminBundle\Entity\AgenciaUsuario;
use Admin\AdminBundle\Form\AgenciaType;
use DateTime;

/**
 * Agencia controller.
 *
 * @Route("/dashboard/agencia")
 */
class AgenciaController extends Controller {

    /**
     * Lists all Agencia entities.
     *
     * @Route("/", name="dashboard_agencia")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AdminAdminBundle:Agencia')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Agencia entity.
     *
     * @Route("/", name="dashboard_agencia_create")
     * @Method("POST")
     * @Template("AdminAdminBundle:Agencia:new.html.twig")
     */
    public function createAction(Request $request) {
        $entity = new Agencia();
        $AgenciaUsuario = new AgenciaUsuario();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $date = new DateTime('now', new \DateTimeZone('America/Bogota'));
            /// empezamos a realizar el proseso de registro del usuario para la agencia
            //creamos usuario con rol_agenc para que pueda iniciar sesion
            $user1 = new User();
            //agregamos email
            $em = $this->getDoctrine()->getManager();
            $user1->setEmail($entity->getEmail());
            //colocamos email como nick name
            $user1->setUsername($entity->getEmail());
            $user1->setNombre($entity->getNomsRepLegal());
            $user1->setApellidos($entity->getApellsRepLegal());
            $user1->setTelefono($entity->getTelefono());
            //numedo de documento de identidad como contrasenia 
            $user1->setPlainPassword(1234);
            $user1->setEnabled(true);
            $user1->addRole('ROLE_AGENC');
            $em->persist($user1);
            ////finalizamos el proceso de registro de usuario de la agencia
            ///creamos agencia
            $entity->setFecha($date);
            $entity->setFechaupdate($date);
            $em->persist($entity);
            $AgenciaUsuario->setFecha($date);
            $AgenciaUsuario->setFechaupdate($date);
            $AgenciaUsuario->setIdAgencia($entity);
            $AgenciaUsuario->setIdUsuario($user1);
            $em->persist($AgenciaUsuario);
            $em->flush();
            return $this->redirect($this->generateUrl('dashboard_agencia_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
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
            'action' => $this->generateUrl('dashboard_agencia_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Agencia entity.
     *
     * @Route("/new", name="dashboard_agencia_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        $entity = new Agencia();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Agencia entity.
     *
     * @Route("/{id}", name="dashboard_agencia_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminAdminBundle:Agencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Agencia entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Agencia entity.
     *
     * @Route("/{id}/edit", name="dashboard_agencia_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminAdminBundle:Agencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Agencia entity.');
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
     * Creates a form to edit a Agencia entity.
     *
     * @param Agencia $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Agencia $entity) {
        $form = $this->createForm(new AgenciaType(), $entity, array(
            'action' => $this->generateUrl('dashboard_agencia_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing Agencia entity.
     *
     * @Route("/{id}", name="dashboard_agencia_update")
     * @Method("PUT")
     * @Template("AdminAdminBundle:Agencia:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AdminAdminBundle:Agencia')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Agencia entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $date = new DateTime('now', new \DateTimeZone('America/Bogota'));
            $entity->setFechaupdate($date);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('dashboard_agencia_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Agencia entity.
     *
     * @Route("/{id}", name="dashboard_agencia_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AdminAdminBundle:Agencia')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Agencia entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('dashboard_agencia'));
    }

    /**
     * Creates a form to delete a Agencia entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('dashboard_agencia_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', 'submit', array('label' => 'Delete'))
                        ->getForm()
        ;
    }

}
