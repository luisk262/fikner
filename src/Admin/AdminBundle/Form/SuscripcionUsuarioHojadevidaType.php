<?php

namespace Admin\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SuscripcionUsuarioHojadevidaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Estado', 'choice', array(
                'choices' => array(
                    'VIGENTE' => 'VIGENTE',
                    'CANCELADO' => 'CANCELADO',
                    'TERMINADO' => 'TERMINADO',
                ),
                'required' => True,
                'label' => 'ESTADO*',
                'empty_value' => 'Seleccione',
                'empty_data' => null
            ))
            ->add('fecha_vencimiento')
            ->add('idHojadevida')
            ->add('idSuscripcion')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\AdminBundle\Entity\SuscripcionUsuarioHojadevida'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'admin_adminbundle_suscripcionusuariohojadevida';
    }
}
