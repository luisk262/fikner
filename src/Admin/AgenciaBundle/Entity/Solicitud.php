<?php

namespace Admin\AgenciaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Solicitud
 *
 * @ORM\Table(name="solicitud")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class Solicitud {

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
     * @ORM\Column(name="Nombre", type="string", length=100, nullable=false)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="Observaciones", type="text", length=65535, nullable=true)
     */
    private $observaciones;

    /**
     * @var string
     *
     * @ORM\Column(name="Estado", type="string", length=100, nullable=true)
     */
    private $estado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Fechaprogramada", type="date", nullable=true)
     */
    private $fechaprogramada;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Fecha", type="datetime", nullable=true)
     */
    /**
     * @var \Activo
     *
     * @ORM\Column(name="Privado", type="boolean", nullable=true)
     */
    private $privado;
     /**
     * @var \Agencia
     *
     * @ORM\ManyToOne(targetEntity="Admin\AdminBundle\Entity\Agencia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Agencia", referencedColumnName="id")
     * })
     */
    private $idAgencia;
     /**
     * @var \FosUser
     *
     * @ORM\ManyToOne(targetEntity="Admin\AdminBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Usuario", referencedColumnName="id")
     * })
     */
    private $idUsuario;
    
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Solicitud
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
     * Set observaciones
     *
     * @param string $observaciones
     *
     * @return Solicitud
     */
    public function setObservaciones($observaciones)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones
     *
     * @return string
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set estado
     *
     * @param string $estado
     *
     * @return Solicitud
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set fechaprogramada
     *
     * @param \DateTime $fechaprogramada
     *
     * @return Solicitud
     */
    public function setFechaprogramada($fechaprogramada)
    {
        $this->fechaprogramada = $fechaprogramada;

        return $this;
    }

    /**
     * Get fechaprogramada
     *
     * @return \DateTime
     */
    public function getFechaprogramada()
    {
        return $this->fechaprogramada;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return Solicitud
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
     * @return Solicitud
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set privado
     *
     * @param boolean $privado
     *
     * @return Solicitud
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
     * Set idAgencia
     *
     * @param \Admin\AdminBundle\Entity\Agencia $idAgencia
     *
     * @return Solicitud
     */
    public function setIdAgencia(\Admin\AdminBundle\Entity\Agencia $idAgencia = null)
    {
        $this->idAgencia = $idAgencia;
    
        return $this;
    }

    /**
     * Get idAgencia
     *
     * @return \Admin\AdminBundle\Entity\Agencia
     */
    public function getIdAgencia()
    {
        return $this->idAgencia;
    }

    /**
     * Set idUsuario
     *
     * @param \Admin\AdminBundle\Entity\User $idUsuario
     *
     * @return Solicitud
     */
    public function setIdUsuario(\Admin\AdminBundle\Entity\User $idUsuario = null)
    {
        $this->idUsuario = $idUsuario;
    
        return $this;
    }

    /**
     * Get idUsuario
     *
     * @return \Admin\AdminBundle\Entity\User
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }
}
