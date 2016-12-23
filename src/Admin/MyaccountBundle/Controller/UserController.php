<?php

namespace Admin\MyaccountBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\AdminBundle\Entity\User;
use Admin\AdminBundle\Form\UserType;

/**
 * User controller.
 *
 * @Route("/Myaccount/user")
 */
class UserController extends Controller {

    /**
     * Finds and displays a User entity.
     *
     * @Route("/", name="Myaccount_user_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction() {
        $em = $this->getDoctrine()->getManager();
        $security_context = $this->get('security.token_storage');
        //definimos que usuario se encuentra logeado
        $security_token = $security_context->getToken();
        $user = $security_token->getUser();
        return array(
            'entity' => $user,
            'idfoto' => null,
            'Image' => null
        );
    }

    /**
     * Displays a form to edit an existing User entity.
     *
     * @Route("/edit", name="Myaccount_user_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction() {
        $em = $this->getDoctrine()->getManager();
        $security_context = $this->get('security.token_storage');
        //definimos que usuario se encuentra logeado
        $security_token = $security_context->getToken();
        $user = $security_token->getUser();
        $editForm = $this->createEditForm($user);
        return array(
            'entity' => $user,
            'edit_form' => $editForm->createView(),
            'idfoto' => null,
            'Image' => null
        );
    }

    /**
     * Creates a form to edit a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(User $entity) {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('Myaccount_user_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));
        $form->remove('roles');
        $form->add('fechaNaci', 'date', array(
                    'years' => range(date('Y') - 95, date('Y')),
                    'label' => 'Fecha de nacimimiento',
                    'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
                ));
        
        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing User entity.
     *
     * @Route("/{id}", name="Myaccount_user_update")
     * @Method("PUT")
     * @Template("AdminAdminBundle:User:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AdminAdminBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $entity->setUsername($entity->getEmail());
            $UHojadevida = $em->getRepository('AdminAdminBundle:UsuarioHojadevida')->findOneByidUsuario($id);
            $UHojadevida->getIdHojadevida()->setNombre($entity->getNombre());
            $UHojadevida->getIdHojadevida()->setApellido($entity->getApellidos());
            $UHojadevida->getIdHojadevida()->setEmailPersonal($entity->getEmail());
            $em->persist($UHojadevida);
            $em->flush();
            return $this->redirect($this->generateUrl('Myaccount_user_edit'));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
        );
    }
}
