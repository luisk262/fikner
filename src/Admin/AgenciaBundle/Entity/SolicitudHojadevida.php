<?php

namespace Admin\AgenciaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SolicitudHojadevida
 *
 * @ORM\Table(name="solicitud_hojadevida", indexes={@ORM\Index(name="Id_Solicitud", columns={"id"}), @ORM\Index(name="Id_Hojadevida", columns={"id"})})
 * @ORM\Entity
 */
class SolicitudHojadevida
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
     * @var \Solicitud
     *
     * @ORM\ManyToOne(targetEntity="Solicitud")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Solicitud", referencedColumnName="id")
     * })
     */
    private $idSolicitud;
     /**
     * @var \Hojadevida
     *
     * @ORM\ManyToOne(targetEntity="\Admin\AdminBundle\Entity\Hojadevida" ,cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Hojadevida", referencedColumnName="id")
     * })
     */
    private $idHojadevida;


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
     * Set idSolicitud
     *
     * @param integer $idSolicitud
     *
     * @return SolicitudHojadevida
     */
    public function setIdSolicitud($idSolicitud)
    {
        $this->idSolicitud = $idSolicitud;
    
        return $this;
    }

    /**
     * Get idSolicitud
     *
     * @return integer
     */
    public function getIdSolicitud()
    {
        return $this->idSolicitud;
    }

   

    
    /**
     * Set idHojadevida
     *
     * @param \Admin\AdminBundle\Entity\Hojadevida $idHojadevida
     *
     * @return SolicitudHojadevida
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
}
