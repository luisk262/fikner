<?php

namespace Admin\AgenciaBundle\Form;

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
                ->add('Estado', 'choice', array(
                    'choices' => array(
                        'Activo' => 'Activo',
                        'Pendiente' => 'Pendiente',
                        'Vetado' => 'Vetado'
                    ),
                    'required' => True,
                    'label'=>'Estado*',
                    'empty_value' => 'Seleccione',
                    'empty_data' => null
                ))
            ->add('Tags','text',array('label'=>'Perfiles'))                
            ->add('categoria', 'choice', array(
                    'choices' => array(
                        '' => 'Seleccione',
                        'Extra A' => 'Extra A',
                        'Extra AA' => 'Extra AA',
                        'Extra AAA' => 'Extra AAA',
                        'Figurante' => 'Figurante',
                        'Actor en formacion' => 'Actor en formación',
                        'Actor' => 'Actor',
                        'Modelo A' => 'Modelo A',
                        'Modelo AA' => 'Modelo AA',
                        'Modelo AAA' => 'Modelo AAA'                        
                    ),
                    'empty_data' => null,
                    'required' => true,
                'label'=>'Categoría*'
                ))
                ->add('calificacion','choice', array(
                    'choices' => array(
                        '' => 'Seleccione',
                        '1' => '★',
                        '2' => '★★',
                        '3' => '★★★',
                        '4' => '★★★★',
                        '5' => '★★★★★'                        
                    ),
                    'empty_data' => null,
                    'required' => true,
                'label'=>'Calidad de book*'
                ))
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
