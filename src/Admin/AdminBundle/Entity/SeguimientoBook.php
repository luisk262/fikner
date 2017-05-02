<?php

namespace Admin\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SeguimientoBook
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Admin\AdminBundle\Repository\SeguimientoBookRepository")
 */
class SeguimientoBook
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
     * @var string
     *
     * @ORM\Column(name="ipserver", type="string", length=12)
     */
    private $ipServer;

    /**
     * @var \Hojadevida
     *
     * @ORM\ManyToOne(targetEntity="Hojadevida" ,cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Hojadevida", referencedColumnName="id")
     * })
     */
    private $idHojadevida;
      /**
     * @var \DateTime
     *
     * @ORM\Column(name="Fecha", type="datetime", nullable=false)
     */
    private $fechavisita;

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
     * Set ipServer
     *
     * @param string $ipServer
     *
     * @return SeguimientoBook
     */
    public function setIpServer($ipServer)
    {
        $this->ipServer = $ipServer;
    
        return $this;
    }

    /**
     * Get ipServer
     *
     * @return string
     */
    public function getIpServer()
    {
        return $this->ipServer;
    }

    /**
     * Set fechavisita
     *
     * @param \DateTime $fechavisita
     *
     * @return SeguimientoBook
     */
    public function setFechavisita($fechavisita)
    {
        $this->fechavisita = $fechavisita;
    
        return $this;
    }

    /**
     * Get fechavisita
     *
     * @return \DateTime
     */
    public function getFechavisita()
    {
        return $this->fechavisita;
    }

    /**
     * Set idHojadevida
     *
     * @param \Admin\AdminBundle\Entity\Hojadevida $idHojadevida
     *
     * @return SeguimientoBook
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
