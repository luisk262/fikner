<?php

namespace Admin\AgenciaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SolicitudHojadevidaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idSolicitud')
            ->add('idHojadevida')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\AgenciaBundle\Entity\SolicitudHojadevida'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_agenciabundle_solicitudhojadevida';
    }
}
