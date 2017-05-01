<?php

namespace Admin\MyaccountBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Admin\AdminBundle\Entity\User;
use Admin\MyaccountBundle\Form\RegistrationType;

/**
 * User controller.
 *
 * @Route("/registration")
 */
class RegistrationController extends Controller {

    /**
     * Creates a new User entity.
     *
     * @Route("/", name="registration_create")
     * @Method("POST")
     * @Template("AdminMyaccountBundle:Registration:new.html.twig")
     */
    public function createAction(Request $request) {
        $entity = new User();
        $idAgencia = $request->query->get('idAgencia');
        $idReclutador = $request->query->get('idReclutador');
        $form = $this->createCreateForm($entity,$idAgencia,$idReclutador);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                            'SELECT u
                        FROM AdminAdminBundle:User u
                        WHERE u.username  =:username'
                    )->setParameter('username', $entity->getEmail());
            if ($query->getResult()) {
                $error=true;
                return $this->redirect(
                                $this->generateUrl('registration_msg', array('id' => $entity->getId(),
                                    'nombre' => $entity->getNombre(),
                                    'apellidos' => $entity->getApellidos(),
                                    'error'=>$error,
                                    'idAgencia'=>$idAgencia,
                                    'idReclutador'=>$idReclutador
                ))); 
            } else {
                $entity->setUsername($entity->getEmail());
                $entity->setEnabled(true);
                $em->persist($entity);
                $em->flush();
                $error=false;
                return $this->redirect(
                                $this->generateUrl('registration_msg', array('id' => $entity->getId(),
                                    'nombre' => $entity->getNombre(),
                                    'apellidos' => $entity->getApellidos(),
                                    'error'=>$error,
                                    'idAgencia'=>$idAgencia,
                                    'idReclutador'=>$idReclutador
                
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
    private function createCreateForm(User $entity,$idAgencia,$idReclutador) {
        $form = $this->createForm(new RegistrationType(), $entity, array(
            'action' => $this->generateUrl('registration_create',array('idAgencia'=>$idAgencia,'idReclutador'=>$idReclutador)),
            'method' => 'POST',
        ));
        $form->add('nombre', 'text', array('label' => 'Nombres*','max_length'=>30));
        $form->add('apellidos', 'text', array('label' => 'Apellidos*','max_length'=>30));
        $form->add('telefono', 'text', array('max_length'=>13,'label' => 'Telefono*'));
        $form->add('fechanaci', 'date', array(
                    'years' => range(date('Y') - 7,date('Y')- 95),
                    'required' => True,
                    'label' => 'Fecha de nacimimiento',
                    'empty_value' => array('year' => 'Año', 'month' => 'Mes', 'day' => 'Dia'),
                ));
        $form->add('email', 'repeated', array(
            'type' => 'email',
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'invalid_message' => 'Los dos correos no coinciden',
            'first_options'  => array('label' => 'Email', 'attr'=>array('placeholder' => 'Email')),
            'second_options' => array('label' => 'Repita Email', 'attr'=>array('placeholder' => 'Confirme Email')),
        ));
        $form->add('plainPassword', 'repeated', array(
            'type' => 'password',
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'invalid_message' => 'fos_user.password.mismatch',            
            'first_options'  => array('label' => 'Contraseña', 'attr'=>array('placeholder' => 'Contraseña')),
            'second_options' => array('label' => 'Confirme Contraseña', 'attr'=>array('placeholder' => 'Confirme Contraseña'))
        ));
        $form->add('submit', 'submit', array('label' => 'Crear Cuenta'));

        return $form;
    }

    /**
     * Displays a form to create a new User entity.
     *
     * @Route("/", name="registration_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction(Request $request) {
        //Asignamos el parametro url para luego pasarlo a ajax
        $idAgencia = $request->query->get('id');
        $idReclutador = $request->query->get('idReclutador');
        $em = $this->getDoctrine()->getManager();
        $entity=$em->getRepository('AdminAdminBundle:Agencia')->find($idAgencia);
        return array(
            'entity' => $entity,
            'idAgencia'=>$idAgencia,
            'idReclutador'=>$idReclutador
        );
    }

}
