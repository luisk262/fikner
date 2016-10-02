<?php
namespace Admin\MyaccountBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PerfilType extends AbstractType {

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('telCasa','text',array('max_length'=>10,'label'=>'Teléfono fijo','required' =>false))
                ->add('telCe','text',array('max_length'=>10,'label'=>'Teléfono Celular*','required' => true))
                ->add('telefonoAdic','text',array('max_length'=>13,'label'=>'Whatsapp','required' =>false))
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
                ->add('nit','text',array('max_length'=>11,'required' => true,'label' => 'Documento *','attr'=>array('placeholder' => 'Ejemplo( 1026265423 )')))                
                ->add('sexo', 'choice', array(
                    'choices' => array(
                        'Masculino' => 'Hombre',
                        'Femenino' => 'Mujer',
                        'Otro' => 'Otro',
                    ),
                    'required' => True,
                    'label'=>'Genero*',
                    'empty_value' => 'Seleccione tipo',
                    'empty_data' => null
                ))
                ->add('Campoartistico', 'choice', array(
                    'choices' => array(
                        'Actor' => 'Actor',
                        'Actriz' => 'Actriz',
                        'Extra' => 'Extra',
                        'Figurante' => 'Figurante',
                        'Modelo' => 'Modelo',
                        'Otro' => 'Otro',
                    ),
                    'required' =>False,
                    'label'=>'Campo artístico',
                    'empty_value' => 'Seleccione tipo',
                    'empty_data' => null
                ))
                ->add('Estudios', 'choice', array(
                    'choices' => array(
                        'Ninguno' => 'Ninguno',
                        'Modelaje' => 'Modelaje',
                        'Actuacion' => 'Actuacion',
                        'Modelaje y Actuacion' => 'Modelaje y Actuacion',
                    ),
                    'required' =>False,
                    'label'=>'Estudios realizados',
                    'empty_value' => 'Seleccione tipo',
                    'empty_data' => null
                ))
                ->add('paisnacimiento','text',array('label'=>'País de nacimiento*','attr'=>array('placeholder' => 'Ejemplo( Argentina  )')))
                ->add('ciudadresidencia','text', array('label'=>'Ciudad de residencia*','required' => true,'attr'=>array('placeholder' => 'Ejemplo( Bogota  )')))
                ->add('dirCasa','text',array('label'=>'Dirección de  residencia','required' => false,'attr'=>array('placeholder' => 'Ejemplo( Calle 41A#12-12  )')))
                ->add('estatura','choice',array(
                'placeholder'   => 'Estatura en Centímetros',
                'choices'       => range(0,273),
                'required'      => true))
                ->add('piel', 'choice', array(
                    'choices' => array(
                        'I' => 'I-Muy Blanca',
                        'II' => 'II-Blanca',
                        'III'=>'III-Ligeramente morena',
                        'IV'=>'IV-Morena',
                        'V'=>'V-Muy Morena ',
                        'VI'=>'VI-Negra'
                ),
                    'required' => True,
                    'label'=>'Color de piel*',
                    'empty_value' => 'Seleccione ',
                    'empty_data' => null
                ))
                ->add('ojos', 'choice', array(
                    'choices' => array(
                        'Negro' => 'Negro',
                        'Castaño' => 'Castaño',
                        'Ambar' => 'Ambar',
                        'Avellana'=>'Avellana',
                        'Verde'=>'Verde',
                        'Azul'=>'Azul',
                        'Gris'=>'Gris',
                        'Heterocromia'=>'Heterocromia'
                    ),
                    'required' => True,
                    'label'=>'Color de ojos*',
                    'empty_value' => 'Seleccione ',
                    'empty_data' => null
                ))                 
                ->add('pelo', 'choice', array(
                    'choices' => array(
                        'Negro' => 'Negro',
                        'Castaño' => 'Castaño',
                        'Rubio-castañoclaro' => 'Rubio o castaño claro',
                        'Castaño oscuro' => 'Castaño oscuro',
                        'Pelirojo'=>'Pelirojo',
                        'Gris'=>'Gris o canoso',
                        'Blanco'=>'Blanco',
                        'Sin cabello'=>'Sin cabello',
                        'Otro'=>'Otro'
                    ),
                    'required' => True,
                    'label'=>'Color de cabello*',
                    'empty_value' => 'Seleccione ',
                    'empty_data' => null
                ))
                ->add('peso','choice',array(
                'placeholder'   => 'Peso en kg',
                'choices'       => range(0,597),
                'required'      => false))
                ->add('experienciaTv','textarea',array('label'=>'Experiencia','required' => false,'attr'=>array('rows' => 33,'placeholder' => 'Digite aqui su experiencia en Televisión,Btl protocolo (etc)')))
                ->add('deportes','text',array('required' => false,'attr'=>array('placeholder' => 'Ejemplo( Futbol,MMA,Boxing )')))
                ->add('habilidades','text',array('required' => false,'attr'=>array('placeholder' => 'Ejemplo( Cantante,Pintor,Imitador vocal )')))
                ->add('idiomas','text',array('required' => false,'attr'=>array('placeholder' => 'Ejemplo( Ingles,Aleman,Frances )')))
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
                        '0-1 Meses' => '0-1 Meses',
                        '1-3 Meses' => '1-3 Meses',
                        '3-6 Meses' => '3-6 Meses',
                        '6-9 Meses' => '6-9 Meses',
                        '9-12 Meses' => '9-12 Meses',
                        '12-18 Meses' => '12-18 Meses',
                        '12-24 Meses' => '12-24 Meses',
                        '24-36 Meses' => '24-36 Meses',
                        'USA-XXXS(0)' =>'USA-XXXS(0)',
                        'USA-XXS(2)' =>'USA-XXS(2)',
                        'USA-XS(4)' => 'USA-XS(4)',
                        'USA-S(6)' => 'USA-S(6)',
                        'USA-M(8)' => 'USA-M(8)',
                        'USA-L(10)' => 'USA-L(10)',
                        'USA-XL(12)' => 'USA-XL(12)',
                        ),
                    'required' => True,
                    'label'=>'Talla de camisa*',
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
                        'XS(26-28)' => 'XS(26-28)',
                        'S(29-31)' => 'S(29-31)',
                        'M(32-34)' => 'M(32-34)',
                        'L(35-37)' => 'L(35-37)',
                        'XL(38-40)' => 'XL(38-40)',
                        'XXL(41-43)' => 'XXL(41-43)'
                        ),
                    'required' => True,
                    'label'=>'Talla de pantalón*',
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
