<?php

namespace Admin\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Hojadevida
 *
 * @ORM\Table(name="hojadevida")
 * @ORM\Entity
 */
class Hojadevida {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Nombre", type="string", length=30, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="Apellido", type="string", length=30, nullable=false)
     */
    private $apellido;

    /**
     * @var string
     *
     * @ORM\Column(name="Tel_Casa", type="string", length=30, nullable=true)
     */
    private $telCasa;

    /**
     * @var string
     *
     * @ORM\Column(name="Tel_Ce", type="string", length=30, nullable=false)
     */
    private $telCe;

    /**
     * @var string
     *
     * @ORM\Column(name="Telefono_Adic", type="string", length=30, nullable=true)
     */
    private $telefonoAdic;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Fecha_Nac", type="datetime", nullable=false)
     */
    private $fechaNac;

    /**
     * @var string
     *
     * @ORM\Column(name="Tipo_Documento", type="string", length=2, nullable=false)
     */
    private $tipoDocumento;

    /**
     * @var string
     *
     * @ORM\Column(name="Nit", type="string", length=15, nullable=false)
     */
    private $nit;

    /**
     * @var string
     *
     * @ORM\Column(name="Sexo", type="string", length=30, nullable=false)
     */
    private $sexo;

    /**
     * @var string
     * @ORM\Column(name="Pais_Nacimiento", type="string", length=40, nullable=true)
     */
    private $paisNacimiento;

    /**
     * @var string
     *
     * @ORM\Column(name="Ciudad_residencia", type="string", length=25, nullable=true)
     */
    private $ciudadresidencia;

    /**
     * @var string
     *
     * @ORM\Column(name="Dir_Casa", type="string", length=40, nullable=true)
     */
    private $dirCasa;

    /**
     * @var string
     *
     * @ORM\Column(name="Email_Personal", type="string", length=40, nullable=false)
     */
    private $emailPersonal;

    /**
     * @var string
     *
     * @ORM\Column(name="Estatura", type="string", length=20, nullable=true)
     */
    private $estatura;

    /**
     * @var string
     *
     * @ORM\Column(name="Piel", type="string", length=30, nullable=true)
     */
    private $piel;

    /**
     * @var string
     *
     * @ORM\Column(name="Ojos", type="string", length=30, nullable=true)
     */
    private $ojos;

    /**
     * @var string
     *
     * @ORM\Column(name="Pelo", type="string", length=30, nullable=true)
     */
    private $pelo;

    /**
     * @var string
     *
     * @ORM\Column(name="Peso", type="string", length=30, nullable=true)
     */
    private $peso;

    /**
     * @var string
     *
     * @ORM\Column(name="Experiencia_TV", type="text", nullable=true)
     */
    private $experienciaTv;

    /**
     * @var string
     *
     * @ORM\Column(name="Deportes", type="string", length=80, nullable=true)
     */
    private $deportes;

    /**
     * @var string
     *
     * @ORM\Column(name="Habilidades", type="string", length=80, nullable=true)
     */
    private $habilidades;

    /**
     * @var string
     *
     * @ORM\Column(name="Idiomas", type="string", length=80, nullable=true)
     */
    private $idiomas;

    /**
     * @var string
     *
     * @ORM\Column(name="Maneja", type="string", length=80, nullable=true)
     */
    private $maneja;

    /**
     * @var string
     *
     * @ORM\Column(name="Entidad_Salud", type="string", length=80, nullable=true)
     */
    private $entidadSalud;

   

    /**
     * @var string
     *
     * @ORM\Column(name="Talla_Camisa", type="string", length=30, nullable=true)
     */
    private $tallaCamisa;

    /**
     * @var string
     *
     * @ORM\Column(name="Talla_Chaqueta", type="string", length=30, nullable=true)
     */
    private $tallaChaqueta;

    /**
     * @var string
     *
     * @ORM\Column(name="Talla_Pantalon", type="string", length=30, nullable=true)
     */
    private $tallaPantalon;

    /**
     * @var string
     *
     * @ORM\Column(name="Talla_Zapato", type="string", length=30, nullable=true)
     */
    private $tallaZapato;
     /**
     * @var string
     *
     * @ORM\Column(name="Estudios", type="string", length=255, nullable=true)
     */
    private $Estudios;
    /**
     * @var string
     *
     * @ORM\Column(name="Campoartistico", type="string", length=255, nullable=true)
     */
    private $Campoartistico;
    /**
     * @var string
     *
     * @ORM\Column(name="Calificacion", type="string", length=2, nullable=true)
     */
    private $Calificacion;
   

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Fecha", type="datetime", nullable=false)
     */
    private $fecha;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FechaUpdate", type="datetime", nullable=true)
     */
    private $fechaupdate;
    
    public function __toString() {
        return $this->nombre;
    }

  
    
   


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Hojadevida
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set apellido
     *
     * @param string $apellido
     *
     * @return Hojadevida
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set telCasa
     *
     * @param string $telCasa
     *
     * @return Hojadevida
     */
    public function setTelCasa($telCasa)
    {
        $this->telCasa = $telCasa;

        return $this;
    }

    /**
     * Get telCasa
     *
     * @return string
     */
    public function getTelCasa()
    {
        return $this->telCasa;
    }

    /**
     * Set telCe
     *
     * @param string $telCe
     *
     * @return Hojadevida
     */
    public function setTelCe($telCe)
    {
        $this->telCe = $telCe;

        return $this;
    }

    /**
     * Get telCe
     *
     * @return string
     */
    public function getTelCe()
    {
        return $this->telCe;
    }

    /**
     * Set telefonoAdic
     *
     * @param string $telefonoAdic
     *
     * @return Hojadevida
     */
    public function setTelefonoAdic($telefonoAdic)
    {
        $this->telefonoAdic = $telefonoAdic;

        return $this;
    }

    /**
     * Get telefonoAdic
     *
     * @return string
     */
    public function getTelefonoAdic()
    {
        return $this->telefonoAdic;
    }

    /**
     * Set fechaNac
     *
     * @param \DateTime $fechaNac
     *
     * @return Hojadevida
     */
    public function setFechaNac($fechaNac)
    {
        $this->fechaNac = $fechaNac;

        return $this;
    }

    /**
     * Get fechaNac
     *
     * @return \DateTime
     */
    public function getFechaNac()
    {
        return $this->fechaNac;
    }

    /**
     * Set tipoDocumento
     *
     * @param string $tipoDocumento
     *
     * @return Hojadevida
     */
    public function setTipoDocumento($tipoDocumento)
    {
        $this->tipoDocumento = $tipoDocumento;

        return $this;
    }

    /**
     * Get tipoDocumento
     *
     * @return string
     */
    public function getTipoDocumento()
    {
        return $this->tipoDocumento;
    }

    /**
     * Set nit
     *
     * @param string $nit
     *
     * @return Hojadevida
     */
    public function setNit($nit)
    {
        $this->nit = $nit;

        return $this;
    }

    /**
     * Get nit
     *
     * @return string
     */
    public function getNit()
    {
        return $this->nit;
    }

    /**
     * Set sexo
     *
     * @param string $sexo
     *
     * @return Hojadevida
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return string
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set paisNacimiento
     *
     * @param string $paisNacimiento
     *
     * @return Hojadevida
     */
    public function setPaisNacimiento($paisNacimiento)
    {
        $this->paisNacimiento = $paisNacimiento;

        return $this;
    }

    /**
     * Get paisNacimiento
     *
     * @return string
     */
    public function getPaisNacimiento()
    {
        return $this->paisNacimiento;
    }

    /**
     * Set ciudadresidencia
     *
     * @param string $ciudadresidencia
     *
     * @return Hojadevida
     */
    public function setCiudadresidencia($ciudadresidencia)
    {
        $this->ciudadresidencia = $ciudadresidencia;

        return $this;
    }

    /**
     * Get ciudadresidencia
     *
     * @return string
     */
    public function getCiudadresidencia()
    {
        return $this->ciudadresidencia;
    }

    /**
     * Set dirCasa
     *
     * @param string $dirCasa
     *
     * @return Hojadevida
     */
    public function setDirCasa($dirCasa)
    {
        $this->dirCasa = $dirCasa;

        return $this;
    }

    /**
     * Get dirCasa
     *
     * @return string
     */
    public function getDirCasa()
    {
        return $this->dirCasa;
    }

    /**
     * Set emailPersonal
     *
     * @param string $emailPersonal
     *
     * @return Hojadevida
     */
    public function setEmailPersonal($emailPersonal)
    {
        $this->emailPersonal = $emailPersonal;

        return $this;
    }

    /**
     * Get emailPersonal
     *
     * @return string
     */
    public function getEmailPersonal()
    {
        return $this->emailPersonal;
    }

    /**
     * Set estatura
     *
     * @param string $estatura
     *
     * @return Hojadevida
     */
    public function setEstatura($estatura)
    {
        $this->estatura = $estatura;

        return $this;
    }

    /**
     * Get estatura
     *
     * @return string
     */
    public function getEstatura()
    {
        return $this->estatura;
    }

    /**
     * Set piel
     *
     * @param string $piel
     *
     * @return Hojadevida
     */
    public function setPiel($piel)
    {
        $this->piel = $piel;

        return $this;
    }

    /**
     * Get piel
     *
     * @return string
     */
    public function getPiel()
    {
        return $this->piel;
    }

    /**
     * Set ojos
     *
     * @param string $ojos
     *
     * @return Hojadevida
     */
    public function setOjos($ojos)
    {
        $this->ojos = $ojos;

        return $this;
    }

    /**
     * Get ojos
     *
     * @return string
     */
    public function getOjos()
    {
        return $this->ojos;
    }

    /**
     * Set pelo
     *
     * @param string $pelo
     *
     * @return Hojadevida
     */
    public function setPelo($pelo)
    {
        $this->pelo = $pelo;

        return $this;
    }

    /**
     * Get pelo
     *
     * @return string
     */
    public function getPelo()
    {
        return $this->pelo;
    }

    /**
     * Set peso
     *
     * @param string $peso
     *
     * @return Hojadevida
     */
    public function setPeso($peso)
    {
        $this->peso = $peso;

        return $this;
    }

    /**
     * Get peso
     *
     * @return string
     */
    public function getPeso()
    {
        return $this->peso;
    }

    /**
     * Set experienciaTv
     *
     * @param string $experienciaTv
     *
     * @return Hojadevida
     */
    public function setExperienciaTv($experienciaTv)
    {
        $this->experienciaTv = $experienciaTv;

        return $this;
    }

    /**
     * Get experienciaTv
     *
     * @return string
     */
    public function getExperienciaTv()
    {
        return $this->experienciaTv;
    }

    /**
     * Set deportes
     *
     * @param string $deportes
     *
     * @return Hojadevida
     */
    public function setDeportes($deportes)
    {
        $this->deportes = $deportes;

        return $this;
    }

    /**
     * Get deportes
     *
     * @return string
     */
    public function getDeportes()
    {
        return $this->deportes;
    }

    /**
     * Set habilidades
     *
     * @param string $habilidades
     *
     * @return Hojadevida
     */
    public function setHabilidades($habilidades)
    {
        $this->habilidades = $habilidades;

        return $this;
    }

    /**
     * Get habilidades
     *
     * @return string
     */
    public function getHabilidades()
    {
        return $this->habilidades;
    }

    /**
     * Set idiomas
     *
     * @param string $idiomas
     *
     * @return Hojadevida
     */
    public function setIdiomas($idiomas)
    {
        $this->idiomas = $idiomas;

        return $this;
    }

    /**
     * Get idiomas
     *
     * @return string
     */
    public function getIdiomas()
    {
        return $this->idiomas;
    }

    /**
     * Set maneja
     *
     * @param string $maneja
     *
     * @return Hojadevida
     */
    public function setManeja($maneja)
    {
        $this->maneja = $maneja;

        return $this;
    }

    /**
     * Get maneja
     *
     * @return string
     */
    public function getManeja()
    {
        return $this->maneja;
    }

    /**
     * Set entidadSalud
     *
     * @param string $entidadSalud
     *
     * @return Hojadevida
     */
    public function setEntidadSalud($entidadSalud)
    {
        $this->entidadSalud = $entidadSalud;

        return $this;
    }

    /**
     * Get entidadSalud
     *
     * @return string
     */
    public function getEntidadSalud()
    {
        return $this->entidadSalud;
    }

    /**
     * Set tallaCamisa
     *
     * @param string $tallaCamisa
     *
     * @return Hojadevida
     */
    public function setTallaCamisa($tallaCamisa)
    {
        $this->tallaCamisa = $tallaCamisa;

        return $this;
    }

    /**
     * Get tallaCamisa
     *
     * @return string
     */
    public function getTallaCamisa()
    {
        return $this->tallaCamisa;
    }

    /**
     * Set tallaChaqueta
     *
     * @param string $tallaChaqueta
     *
     * @return Hojadevida
     */
    public function setTallaChaqueta($tallaChaqueta)
    {
        $this->tallaChaqueta = $tallaChaqueta;

        return $this;
    }

    /**
     * Get tallaChaqueta
     *
     * @return string
     */
    public function getTallaChaqueta()
    {
        return $this->tallaChaqueta;
    }

    /**
     * Set tallaPantalon
     *
     * @param string $tallaPantalon
     *
     * @return Hojadevida
     */
    public function setTallaPantalon($tallaPantalon)
    {
        $this->tallaPantalon = $tallaPantalon;

        return $this;
    }

    /**
     * Get tallaPantalon
     *
     * @return string
     */
    public function getTallaPantalon()
    {
        return $this->tallaPantalon;
    }

    /**
     * Set tallaZapato
     *
     * @param string $tallaZapato
     *
     * @return Hojadevida
     */
    public function setTallaZapato($tallaZapato)
    {
        $this->tallaZapato = $tallaZapato;

        return $this;
    }

    /**
     * Get tallaZapato
     *
     * @return string
     */
    public function getTallaZapato()
    {
        return $this->tallaZapato;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Hojadevida
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set fechaupdate
     *
     * @param \DateTime $fechaupdate
     *
     * @return Hojadevida
     */
    public function setFechaupdate($fechaupdate)
    {
        $this->fechaupdate = $fechaupdate;

        return $this;
    }

    /**
     * Get fechaupdate
     *
     * @return \DateTime
     */
    public function getFechaupdate()
    {
        return $this->fechaupdate;
    }

    /**
     * Set estudios
     *
     * @param string $estudios
     *
     * @return Hojadevida
     */
    public function setEstudios($estudios)
    {
        $this->Estudios = $estudios;

        return $this;
    }

    /**
     * Get estudios
     *
     * @return string
     */
    public function getEstudios()
    {
        return $this->Estudios;
    }

    /**
     * Set campoartistico
     *
     * @param string $campoartistico
     *
     * @return Hojadevida
     */
    public function setCampoartistico($campoartistico)
    {
        $this->Campoartistico = $campoartistico;

        return $this;
    }

    /**
     * Get campoartistico
     *
     * @return string
     */
    public function getCampoartistico()
    {
        return $this->Campoartistico;
    }

    /**
     * Set calificacion
     *
     * @param string $calificacion
     *
     * @return Hojadevida
     */
    public function setCalificacion($calificacion)
    {
        $this->Calificacion = $calificacion;

        return $this;
    }

    /**
     * Get calificacion
     *
     * @return string
     */
    public function getCalificacion()
    {
        return $this->Calificacion;
    }
}
