<?php

namespace Admin\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * seguimientoagencia
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Admin\AdminBundle\Entity\SeguimientoAgenciaRepository")
 */
class SeguimientoAgencia
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
     * @var \Agencia
     *
     * @ORM\ManyToOne(targetEntity="Agencia")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Agencia", referencedColumnName="id")
     * })
     */
    private $idAgencia;

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
     * Set fechavisita
     *
     * @param \DateTime $fechavisita
     *
     * @return seguimientoagencia
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
     * Set idAgencia
     *
     * @param \Admin\AdminBundle\Entity\Agencia $idAgencia
     *
     * @return seguimientoagencia
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
     * Set idHojadevida
     *
     * @param \Admin\AdminBundle\Entity\Hojadevida $idHojadevida
     *
     * @return seguimientoagencia
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
