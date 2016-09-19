<?php

namespace Admin\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AgenciaType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('categoria', 'choice', array(
                    'choices' => array(
                        'Agencia' => 'Agencia',
                        'Individual' => 'Individual'
                    ),
                    'required' => True,
                    'label' => 'Categoria*',
                    'empty_data' => null
                ))
                ->add('nombreagencia')
                ->add('telefono')
                ->add('direccion')
                ->add('nomsRepLegal')
                ->add('apellsRepLegal')
                ->add('email')
                ->add('activo', 'checkbox', array('label' => 'Activa', 'required' => false))
                ->add('privado', 'checkbox', array('label' => 'Privada', 'required' => false))

        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\AdminBundle\Entity\Agencia'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'admin_adminbundle_agencia';
    }

}
