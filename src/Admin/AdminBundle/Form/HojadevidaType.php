<?php

namespace Admin\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class HojadevidaType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
               ->add('nombre','text',array('label'=>'Nombres *','max_length'=>30))
                ->add('apellido','text',array('label'=>'Apellidos *','max_length'=>30))
                ->add('telCasa','text',array('max_length'=>10,'label'=>'Telefono fijo','required' =>false))
                ->add('telCe','text',array('max_length'=>10,'required' => true))
                ->add('telefonoAdic','text',array('max_length'=>13,'label'=>'Whatsapp','required' =>false))
                ->add('fechaNac', 'date', array(
                    'years' => range(date('Y') - 95,date('Y')),
                    'required' => True,
                    'label' => 'Fecha de nacimimiento',
                    'empty_value' => array('year' => 'Year', 'month' => 'Month', 'day' => 'Day'),
                ))
                ->add('tipoDocumento', 'choice', array(
                    'choices' => array(
                        'CC' => 'CC',
                        'TI' => 'TI',
                        'PAS' => 'PAS',
                        'CE' => 'CE'
                    ),
                    'required' => True,
                    'label'=>'Tipo de documento*',
                    'empty_data' => null
                ))
                ->add('nit','text',array('max_length'=>11,'required' => true,'label' => 'Documento *'))
                
                ->add('sexo', 'choice', array(
                    'choices' => array(
                        'Masculino' => 'Masculino',
                        'Femenino' => 'Femenino',
                    ),
                    'required' => True,
                    'label'=>'Sexo*',
                    'empty_value' => 'Seleccione tipo',
                    'empty_data' => null
                ))
                ->add('Campoartistico')
                ->add('Estudios')
                ->add('paisnacimiento')
                ->add('ciudadresidencia','text', array('label'=>'Ciudad de residencia*','required' => true))
                ->add('dirCasa')
                ->add('emailPersonal','email',array('label'=>'Correo Electronico*','required' => true))
                ->add('estatura','number',array('max_length'=>4))
                ->add('piel', 'choice', array(
                    'choices' => array(
                        'Amarilla' => 'Amarilla',
                        'Blanca' => 'Blanca',
                        'Indigena'=>'Indigena',
                        'Mestiza'=>'Mestiza',
                        'Negra'=>'Negra',
                        'Roja'=>'Roja',
                        'Trigeña'=>'Trigeña',
                        
                        'Otro'=>'Otro'
                    ),
                    'required' => True,
                    'label'=>'Color de piel*',
                    'empty_value' => 'Seleccione ',
                    'empty_data' => null
                ))
                ->add('ojos', 'choice', array(
                    'choices' => array(
                        'Cafes' => 'Cafes',
                        'Azules' => 'Azules',
                        'Negros'=>'Negros',
                        'Verdes'=>'Verdes',
                        'Otro'=>'Otro'
                    ),
                    'required' => True,
                    'label'=>'Color de ojos*',
                    'empty_value' => 'Seleccione ',
                    'empty_data' => null
                ))                 
                ->add('pelo', 'choice', array(
                    'choices' => array(
                        'Castaño oscuro' => 'Castaño oscuro',
                        'Castaño' => 'Castaño',
                        'Negro'=>'Negro',
                        'Rojo'=>'Rojo',
                        'Rubio'=>'Rubio',
                        'Otro'=>'Otro'
                    ),
                    'required' => True,
                    'label'=>'Color de cabello*',
                    'empty_value' => 'Seleccione ',
                    'empty_data' => null
                ))
                ->add('peso','number',array('required' => false,'max_length'=>3))
                ->add('experienciaTv')
                ->add('deportes')
                ->add('habilidades')
                ->add('idiomas')
                ->add('maneja', 'choice', array(
                    'choices' => array(
                        'No' => 'No',
                        'Carro' => 'Carro',
                        'Moto'=>'Moto',
                        'Carro y Moto'=>'Carro y Moto',
                        'Otro'=>'Otro'
                    ),
                    'required' => True,
                    'empty_data' => null
                ))
                ->add('entidadSalud')
                ->add('tallaCamisa', 'choice', array(
                    'choices' => array(
                        '1' => '1',
                        '2' => '2',
                        '4' => '4',
                        '6' => '6',
                        '8' => '8',
                        '10' => '10',
                        '12' => '12',
                        'XS' => 'XS',
                        'S' => 'S',
                        'L' => 'L',
                        'M' => 'M',
                        'XL' => 'XL',
                        'XXL' => 'XXL',
                        ),
                    'required' => True,
                    'label'=>'Talla de camisa*',
                    'empty_value' => 'Seleccione ',
                    
                    'empty_data' => null
                ))
               ->add('tallaChaqueta', 'choice', array(
                    'choices' => array(
                        '1' => '1',
                        '2' => '2',
                        '4' => '4',
                        '6' => '6',
                        '8' => '8',
                        '10' => '10',
                        '12' => '12',
                        'XS' => 'XS',
                        'S' => 'S',
                        'L' => 'L',
                        'M' => 'M',
                        'XL' => 'XL',
                        'XXL' => 'XXL',
                        ),
                    'required' => True,
                    'label'=>'Talla de chaqueta*',
                    'empty_value' => 'Seleccione ',
                    'empty_data' => null
                ))
                ->add('tallaPantalon', 'choice', array(
                    'choices' => array(
                         '1' => '1',
                        '2' => '2',
                        '4' => '4',
                        '6' => '6',
                        '8' => '8',
                        '10' => '10',
                        '12' => '12',
                        'XS-28' => 'XS-28',
                        'S-30' => 'S-30',
                        'M-32' => 'M-32',
                        'L-34' => 'L-34',
                        'XL-36' => 'XL-36',
                        'XXL-38' => 'XXL-38'
                        
                        ),
                    'required' => True,
                    'label'=>'Talla de pantalon*',
                    'empty_value' => 'Seleccione ',
                    'empty_data' => null
                ))
                ->add('tallaZapato', 'choice', array(
                    'choices' => array(
                        '2.5  - 32' => '2.5 - 32',
                        '3 - 32.5' => '3 - 32.5',
                        '3.5 - 33' => '3.5 - 33',
                        '4 - 33.5' => '4 - 33.5',
                        '4.5 - 34' => '4.5 - 34',
                        '5 - 35.5' => '5 - 35.5',
                        '5.5 - 36' => '5.5 - 36',
                        '6 - 36.5' => '6 - 36.5',
                        '6.5 - 37' => '6.5 - 37',
                        '7 - 37.5' => '6 - 37.5',
                        '7.5 - 38' => '7.5 - 38',
                        '8 - 38.5' => '8 - 38.5',
                        '8.5 - 39' => '8.5 - 39',
                        '9 - 39.5' => '9 - 39.5',
                        '9.5 - 40' => '9.5 - 40',
                        '10 - 40.5' => '10 - 40.5',
                        '10.5 - 41' => '10.5 - 41',
                        '11 - 41.5' => '11 - 41.5',
                        '11.5 - 42' => '11.5 - 42',
                        '12 - 42.5' => '12 - 42.5',
                        '12.5 - 43' => '12.5 - 43',
                        '13 - 43.5' => '13 - 43.5',
                        '13.5 - 44' => '13.5 - 44',
                        '14 - 44.5' => '14 - 44.5',
                        '14.5 - 45' => '14.5 - 45',
                        '15 - 45.5' => '15 - 45.5',
                        '15.5 - 46' => '15.5 - 46',
                        '16 - 46.5' => '16 - 46.5',
                        '16 - 47.5' => '16.5 - 47',
                        ),
                    'required' => True,
                    'label'=>'Talla de calzado*',
                    'empty_value' => 'Seleccione ',
                    'empty_data' => null
                ))
                
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Admin\AdminBundle\Entity\Hojadevida'
        ));
    }

    /**
     * @return string
     */
    public function getName() {
        return 'admin_adminbundle_hojadevida';
    }

}
