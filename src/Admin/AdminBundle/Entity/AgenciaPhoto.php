<?php

namespace Admin\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HojadevidaPhoto
 *
 * @ORM\Table(name="Agencia_photo", indexes={@ORM\Index(name="Id_Hojadevida", columns={"id"}), @ORM\Index(name="Id_Photo", columns={"Id_Photo"})})
 * @ORM\Entity
 */
class AgenciaPhoto
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
     * @ORM\ManyToOne(targetEntity="Photo")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Photo", referencedColumnName="id")
     * })
     */
    private $idPhoto;
     /**
     * @var \Principal
     *
     * @ORM\Column(name="Principal", type="boolean", nullable=true)
     */
    private $principal;
    
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
     * Set principal
     *
     * @param boolean $principal
     *
     * @return AgenciaPhoto
     */
    public function setPrincipal($principal)
    {
        $this->principal = $principal;

        return $this;
    }

    /**
     * Get principal
     *
     * @return boolean
     */
    public function getPrincipal()
    {
        return $this->principal;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return AgenciaPhoto
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
     * @return AgenciaPhoto
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
     * Set idAgencia
     *
     * @param \Admin\AdminBundle\Entity\Agencia $idAgencia
     *
     * @return AgenciaPhoto
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
     * Set idPhoto
     *
     * @param \Admin\AdminBundle\Entity\Photo $idPhoto
     *
     * @return AgenciaPhoto
     */
    public function setIdPhoto(\Admin\AdminBundle\Entity\Photo $idPhoto = null)
    {
        $this->idPhoto = $idPhoto;

        return $this;
    }

    /**
     * Get idPhoto
     *
     * @return \Admin\AdminBundle\Entity\Photo
     */
    public function getIdPhoto()
    {
        return $this->idPhoto;
    }
}
