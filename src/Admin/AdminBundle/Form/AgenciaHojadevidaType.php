<?php

namespace Admin\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AgenciaHojadevidaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Estado')
            ->add('Tags')
            ->add('categoria')
            ->add('Activo')
            ->add('fecha')
            ->add('fechaupdate')
            ->add('idAgencia')
            ->add('idHojadevida')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\AdminBundle\Entity\AgenciaHojadevida'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_adminbundle_agenciahojadevida';
    }
}
