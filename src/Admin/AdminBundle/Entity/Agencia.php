<?php

namespace Admin\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Agencia
 * @ORM\Table(name="agencia")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Admin\AdminBundle\Repository\AgenciaRepository")
 */
 
class Agencia {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="Nombre_agencia", type="string", length=100)
     */
    private $nombre_agencia;
    /**
     * @var string
     *
     * @ORM\Column(name="Pais", type="string", length=100)
     */
    private $pais;
/**
     * @var string
     *
     * @ORM\Column(name="Ciudad", type="string", length=100)
     */
    private $ciudad;
    /**
     * @var string
     *
     * @ORM\Column(name="Telefono", type="string", length=100)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="Direccion", type="string", length=100, nullable=true)
     */
    private $direccion;

    /**
     * @var string
     *
     * @ORM\Column(name="Nom_Rep_Legal", type="string", length=150)
     */
    private $nomsRepLegal;

    /**
     * @var string
     *
     * @ORM\Column(name="apelli_Rep_Legal", type="string", length=150)
     */
    private $apellsRepLegal;

    /**
     * @var string
     *
     * @ORM\Column(name="Email", type="string", length=150)
     */
    private $email;
/**
     * @var string
     *
     * @ORM\Column(name="Pagina_web", type="string", length=150, nullable=true)
     */
    private $Paginaweb;
    
/**
     * @var string
     *
     * @ORM\Column(name="Grupo_fb", type="string", length=150, nullable=true)
     */
    private $grupofb;
/**
     * @var string
     *
     * @ORM\Column(name="youtube", type="string", length=150, nullable=true)
     */
    private $youtube;
/**
     * @var string
     *
     * @ORM\Column(name="twitter", type="string", length=150, nullable=true)
     */
    private $twitter;
    

    /**
     * @var string
     *
     * @ORM\Column(name="categoria", type="string", length=100)
     */
    private $categoria;
    /**
     * @var string
     *
     * @ORM\Column(name="especialidad", type="string", length=100,nullable=true)
     */
    private $especialidad;

    /**
     * @var string
     *
     * @ORM\Column(name="nit", type="string", length=100)
     */
    private $nit;
    
    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="text", nullable=true)
     */
    private $descripcion;
    /**
     * @var string
     *
     * @ORM\Column(name="firma_email", type="text", nullable=true)
     */
    private $FirmaEmail;
    /**
     * @var \Activo
     *
     * @ORM\Column(name="copia_email", type="boolean", nullable=true)
     */
    private $CopiaEmail;
    /**
     * @var \Activo
     *
     * @ORM\Column(name="Activo", type="boolean", nullable=true)
     */
    private $Activo;
    /**
     * @var \Activo
     *
     * @ORM\Column(name="Privado", type="boolean", nullable=true)
     */
    private $privado;
    
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
     * Set nombreAgencia
     *
     * @param string $nombreAgencia
     *
     * @return Agencia
     */
    public function setNombreAgencia($nombreAgencia)
    {
        $this->nombre_agencia = $nombreAgencia;

        return $this;
    }

    /**
     * Get nombreAgencia
     *
     * @return string
     */
    public function getNombreAgencia()
    {
        return $this->nombre_agencia;
    }

    /**
     * Set pais
     *
     * @param string $pais
     *
     * @return Agencia
     */
    public function setPais($pais)
    {
        $this->pais = $pais;

        return $this;
    }

    /**
     * Get pais
     *
     * @return string
     */
    public function getPais()
    {
        return $this->pais;
    }

    /**
     * Set ciudad
     *
     * @param string $ciudad
     *
     * @return Agencia
     */
    public function setCiudad($ciudad)
    {
        $this->ciudad = $ciudad;

        return $this;
    }

    /**
     * Get ciudad
     *
     * @return string
     */
    public function getCiudad()
    {
        return $this->ciudad;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return Agencia
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return Agencia
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set nomsRepLegal
     *
     * @param string $nomsRepLegal
     *
     * @return Agencia
     */
    public function setNomsRepLegal($nomsRepLegal)
    {
        $this->nomsRepLegal = $nomsRepLegal;

        return $this;
    }

    /**
     * Get nomsRepLegal
     *
     * @return string
     */
    public function getNomsRepLegal()
    {
        return $this->nomsRepLegal;
    }

    /**
     * Set apellsRepLegal
     *
     * @param string $apellsRepLegal
     *
     * @return Agencia
     */
    public function setApellsRepLegal($apellsRepLegal)
    {
        $this->apellsRepLegal = $apellsRepLegal;

        return $this;
    }

    /**
     * Get apellsRepLegal
     *
     * @return string
     */
    public function getApellsRepLegal()
    {
        return $this->apellsRepLegal;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Agencia
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set categoria
     *
     * @param string $categoria
     *
     * @return Agencia
     */
    public function setCategoria($categoria)
    {
        $this->categoria = $categoria;

        return $this;
    }

    /**
     * Get categoria
     *
     * @return string
     */
    public function getCategoria()
    {
        return $this->categoria;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Agencia
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set activo
     *
     * @param boolean $activo
     *
     * @return Agencia
     */
    public function setActivo($activo)
    {
        $this->Activo = $activo;

        return $this;
    }

    /**
     * Get activo
     *
     * @return boolean
     */
    public function getActivo()
    {
        return $this->Activo;
    }
    
    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Agencia
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
     * @return Agencia
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
     * Set nit
     *
     * @param string $nit
     *
     * @return Agencia
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
    public function __toString() {
        return $this->nombre_agencia;
    }

    /**
     * Set privado
     *
     * @param boolean $privado
     *
     * @return Agencia
     */
    public function setPrivado($privado)
    {
        $this->privado = $privado;

        return $this;
    }

    /**
     * Get privado
     *
     * @return boolean
     */
    public function getPrivado()
    {
        return $this->privado;
    }

    /**
     * Set especialidad
     *
     * @param string $especialidad
     *
     * @return Agencia
     */
    public function setEspecialidad($especialidad)
    {
        $this->especialidad = $especialidad;

        return $this;
    }

    /**
     * Get especialidad
     *
     * @return string
     */
    public function getEspecialidad()
    {
        return $this->especialidad;
    }

    /**
     * Set paginaweb
     *
     * @param string $paginaweb
     *
     * @return Agencia
     */
    public function setPaginaweb($paginaweb)
    {
        $this->Paginaweb = $paginaweb;

        return $this;
    }

    /**
     * Get paginaweb
     *
     * @return string
     */
    public function getPaginaweb()
    {
        return $this->Paginaweb;
    }

    /**
     * Set grupofb
     *
     * @param string $grupofb
     *
     * @return Agencia
     */
    public function setGrupofb($grupofb)
    {
        $this->grupofb = $grupofb;

        return $this;
    }

    /**
     * Get grupofb
     *
     * @return string
     */
    public function getGrupofb()
    {
        return $this->grupofb;
    }

    /**
     * Set youtube
     *
     * @param string $youtube
     *
     * @return Agencia
     */
    public function setYoutube($youtube)
    {
        $this->youtube = $youtube;

        return $this;
    }

    /**
     * Get youtube
     *
     * @return string
     */
    public function getYoutube()
    {
        return $this->youtube;
    }

    /**
     * Set twitter
     *
     * @param string $twitter
     *
     * @return Agencia
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * Get twitter
     *
     * @return string
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set firmaEmail
     *
     * @param string $firmaEmail
     *
     * @return Agencia
     */
    public function setFirmaEmail($firmaEmail)
    {
        $this->FirmaEmail = $firmaEmail;

        return $this;
    }

    /**
     * Get firmaEmail
     *
     * @return string
     */
    public function getFirmaEmail()
    {
        return $this->FirmaEmail;
    }

    /**
     * Set copiaEmail
     *
     * @param boolean $copiaEmail
     *
     * @return Agencia
     */
    public function setCopiaEmail($copiaEmail)
    {
        $this->CopiaEmail = $copiaEmail;

        return $this;
    }

    /**
     * Get copiaEmail
     *
     * @return boolean
     */
    public function getCopiaEmail()
    {
        return $this->CopiaEmail;
    }
}
