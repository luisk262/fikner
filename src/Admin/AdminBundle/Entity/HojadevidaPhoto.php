<?php

namespace Admin\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HojadevidaPhoto
 *
 * @ORM\Table(name="hojadevida_photo", indexes={@ORM\Index(name="Id_Hojadevida", columns={"id"}), @ORM\Index(name="Id_Photo", columns={"Id_Photo"})})
 * @ORM\Entity
 */
class HojadevidaPhoto
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
     * @var \Hojadevida
     *
     * @ORM\ManyToOne(targetEntity="Hojadevida",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Hojadevida", referencedColumnName="id")
     * })
     */
    private $idHojadevida;

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
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return HojadevidaPhoto
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
     * @return HojadevidaPhoto
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
     * @return HojadevidaPhoto
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
     * Set idPhoto
     *
     * @param \Admin\AdminBundle\Entity\Photo $idPhoto
     *
     * @return HojadevidaPhoto
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
    /**
     * Set principal
     *
     * @param boolean $principal
     *
     * @return Photo
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
}
