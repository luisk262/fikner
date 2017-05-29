<?php

namespace Admin\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * suscripcionusuariohojadevida
 *
 * @ORM\Entity
 */
class SuscripcionUsuarioHojadevida
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

     /**
     * @var \Hojadevida
     *
     * @ORM\ManyToOne(targetEntity="Hojadevida")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Hojadevida", referencedColumnName="id")
     * })
     */
    private $idHojadevida;

    /**
     * @var \FosUser
     *
     * @ORM\ManyToOne(targetEntity="SuscripcionUsuario" ,cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Suscripcion", referencedColumnName="id")
     * })
     */
    private $idSuscripcion;
    /**
     * @var string
     *
     * @ORM\Column(name="Estado", type="string", length=10)
     */
    private $Estado;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="Fecha_vencimiento", type="datetime", nullable=false)
     */
    private $fecha_vencimiento;

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
     * Set fechaVencimiento
     *
     * @param \DateTime $fechaVencimiento
     *
     * @return SuscripcionUsuarioHojadevida
     */
    public function setFechaVencimiento($fechaVencimiento)
    {
        $this->fecha_vencimiento = $fechaVencimiento;

        return $this;
    }

    /**
     * Get fechaVencimiento
     *
     * @return \DateTime
     */
    public function getFechaVencimiento()
    {
        return $this->fecha_vencimiento;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return SuscripcionUsuarioHojadevida
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
     * @return SuscripcionUsuarioHojadevida
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
     * Set idHojadevida
     *
     * @param \Admin\AdminBundle\Entity\Hojadevida $idHojadevida
     *
     * @return SuscripcionUsuarioHojadevida
     */
    public function setIdHojadevida(\Admin\AdminBundle\Entity\Hojadevida $idHojadevida = null)
    {
        $this->idHojadevida = $idHojadevida;

        return $this;
    }

    /**
     * Get idHojadevida
     *
     * @return \Admin\AdminBundle\Entity\Hojadevida
     */
    public function getIdHojadevida()
    {
        return $this->idHojadevida;
    }

    /**
     * Set idSuscripcion
     *
     * @param \Admin\AdminBundle\Entity\SuscripcionUsuario $idSuscripcion
     *
     * @return SuscripcionUsuarioHojadevida
     */
    public function setIdSuscripcion(\Admin\AdminBundle\Entity\SuscripcionUsuario $idSuscripcion = null)
    {
        $this->idSuscripcion = $idSuscripcion;

        return $this;
    }

    /**
     * Get idSuscripcion
     *
     * @return \Admin\AdminBundle\Entity\SuscripcionUsuario
     */
    public function getIdSuscripcion()
    {
        return $this->idSuscripcion;
    }

    /**
     * Set estado
     *
     * @param string $estado
     *
     * @return SuscripcionUsuarioHojadevida
     */
    public function setEstado($estado)
    {
        $this->Estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->Estado;
    }
}
