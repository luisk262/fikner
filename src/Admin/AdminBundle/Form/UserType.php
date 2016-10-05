<?php

namespace Admin\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('nombre')
                ->add('apellidos')
                ->add('telefono')
                ->add('email')
                ->add('roles', 'choice', array('label' => 'Rol', 'required' => true,
                    'choices' => array('ROLE_ADMIN' => 'ADMINISTRADOR',
                        'ROLE_USER' => 'USUARIO',
                        'ROLE_AGENC' => 'Agencia',
                        'ROLE_RECLU' => 'Reclutador',
                        'ROLE_AGENC' => 'Agencia'
                    ), 'multiple' => true))
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\AdminBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'admin_adminbundle_user';
    }

}
