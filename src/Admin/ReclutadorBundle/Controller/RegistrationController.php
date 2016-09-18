<?php

namespace Admin\ReclutadorBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\AdminBundle\Entity\User;
use Admin\ReclutadorBundle\Form\RegistrationType;

/**
 * User controller.
 *
 * @Route("Reclutador/registration")
 */
class RegistrationController extends Controller {

    /**
     * Creates a new User entity.
     *
     * @Route("/", name="Reclutador_registration_create")
     * @Method("POST")
     * @Template("AdminReclutadorBundle:Registration:new.html.twig")
     */
    public function createAction(Request $request) {
        $entity = new User();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                            'SELECT u
                        FROM AdminAdminBundle:User u
                        WHERE u.username  =:username'
                    )->setParameter('username', $entity->getEmail());
            if ($query->getResult()) {
                $error = true;
                return $this->redirect(
                                $this->generateUrl('Reclutador_registration_msg', array('id' => $entity->getId(),
                                    'nombre' => $entity->getNombre(),
                                    'apellidos' => $entity->getApellidos(),
                                    'error' => $error,
                                    'email' => $entity->getEmail()
                )));
            } else {
                $id = $entity->getId();
                $entity->setUsername($entity->getEmail());
                $entity->addRole('ROLE_RECLU');
                $entity->setEnabled(true);
                $em->persist($entity);
                $em->flush();
            
                ////////////////////
                $error = false;
                return $this->redirect(
                                $this->generateUrl('Reclutador_registration_msg', array('id' => $entity->getId(),
                                    'nombre' => $entity->getNombre(),
                                    'apellidos' => $entity->getApellidos(),
                                    'error' => $error,
                                    'email' => $entity->getEmail()
                )));
            }
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(User $entity) {
        $form = $this->createForm(new RegistrationType(), $entity, array(
            'action' => $this->generateUrl('Reclutador_registration_create'),
            'method' => 'POST',
        ));
        $form->add('nombre', 'text', array('label' => 'Nombres*', 'max_length' => 30));
        $form->add('apellidos', 'text', array('label' => 'Apellidos*', 'max_length' => 30));
        $form->add('telefono', 'text', array('max_length' => 13, 'label' => 'Telefono*'));
        $form->add('fechanaci', 'date', array(
            'years' => range(date('Y') - 17, date('Y') - 95),
            'required' => True,
            'label' => 'Fecha de nacimimiento',
            'empty_value' => array('year' => 'Año', 'month' => 'Mes', 'day' => 'Dia'),
        ));
        $form->add('email', 'repeated', array(
            'type' => 'email',
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'invalid_message' => 'Los dos correos no coinciden',
            'first_options' => array('label' => 'Email', 'attr' => array('placeholder' => 'Email')),
            'second_options' => array('label' => 'Repita Email', 'attr' => array('placeholder' => 'Confirme Email')),
        ));
        $form->add('plainPassword', 'repeated', array(
            'type' => 'password',
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'invalid_message' => 'fos_user.password.mismatch',
            'first_options' => array('label' => 'Contraseña', 'attr' => array('placeholder' => 'Contraseña')),
            'second_options' => array('label' => 'Confirme Contraseña', 'attr' => array('placeholder' => 'Confirme Contraseña'))
        ));
        $form->add('submit', 'submit', array('label' => 'Crear Cuenta'));

        return $form;
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Route("/", name="Reclutador_registration_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        $entity = new User();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

}
