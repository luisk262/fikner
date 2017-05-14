<?php

namespace Admin\AgenciaBundle\Form;

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
                        'Casting' => 'Casting',
                        'BTL' => 'BTL',
                        'Modelaje' => 'Modelaje',
                        'Casting y BTL' => 'Casting y BTL',
                        'Casting y Modelaje' => 'Casting y Modelaje',
                        'BTL y Modelaje' => 'BTL y Modelaje',
                        'Casting,BTL y Modelaje' => 'Casting,BTL y Modelaje',
                    ),
                    'required' => True,
                    'label' => 'Categoría*',
                    'empty_value' => 'Seleccione categoria',
                    'empty_data' => null
                ))
                ->add('nombreagencia', 'text', array('label' => 'Nombre de agencia *', 'required' => true))
                ->add('pais','text',array('label'=>'País *'))
                ->add('ciudad','text',array('label'=>'Ciudad *'))
                ->add('nit','text',array('label'=>'Nit *'))
                ->add('VideoPrincipal','text',array('label'=>'Link video youtube','required' => false))
                ->add('descripcion','textarea',array('label'=>'Descripción ','required' => false,'attr'=>array('rows' => 33,'placeholder' => 'Digite aquí una descripción, actividades  o misión y visión')))
                ->add('telefono', 'text', array('label' => 'Teléfono *', 'required' => true))
                ->add('direccion','text',array('label'=>'Dirección *'))
                ->add('email','email', array('label' => 'Email *', 'required' => true))
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
