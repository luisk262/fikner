<?php

namespace Admin\AgenciaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SolicitudType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre','text',array('label'=>'Nombre empresa cliente'))
            ->add('observaciones','textarea',array('label'=>'Descripción'))
            ->add('fechaprogramada','date',array('label'=>'Fecha programada para la sesión',
                'required' => false,
                'years' => range(date('Y'),date('Y') +3)
                ))
            ->add('privado', 'checkbox', array('label' => 'Privada', 'required' => false));
           ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\AgenciaBundle\Entity\Solicitud'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_agenciabundle_solicitud';
    }
}
