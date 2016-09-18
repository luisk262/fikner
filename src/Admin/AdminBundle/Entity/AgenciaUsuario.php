<?php

namespace Admin\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AgenciaUsuario
 *
 * @ORM\Table(name="agencia_usuario", indexes={@ORM\Index(name="Id_Agencia", columns={"id"}), @ORM\Index(name="Id_Usuario", columns={"Id_Usuario"})})
 * @ORM\Entity
 */
class AgenciaUsuario
{
    /**
     * @var integer
     *
     * @ORM\Column(name="Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Agencia
     *
     * @ORM\ManyToOne(targetEntity="Agencia",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Agencia", referencedColumnName="id")
     * })
     */
    private $idAgencia;

    /**
     * @var \FosUser
     *
     * @ORM\ManyToOne(targetEntity="User")
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idAgencia
     *
     * @param \Admin\AdminBundle\Entity\Agencia $idAgencia
     *
     * @return AgenciaUsuario
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
     * @return AgenciaUsuario
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
    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return AgenciaHojadevida
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
     * @return AgenciaHojadevida
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
}
