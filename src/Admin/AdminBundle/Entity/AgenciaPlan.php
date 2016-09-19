<?php

namespace Admin\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Agencia_Plan
 *
  @ORM\Table(name="Agencia_Plan", indexes={@ORM\Index(name="Id_Agencia", columns={"id"}), @ORM\Index(name="Id_Plan", columns={"Id_Plan"})})
 * @ORM\Entity
 
 *  */
class AgenciaPlan
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
     * @ORM\ManyToOne(targetEntity="Agencia",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Agencia", referencedColumnName="id")
     * })
     */
  private $idAgencia;

   
  
     /**
     * @var \Plan
     *
     * @ORM\ManyToOne(targetEntity="Plan")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Plan", referencedColumnName="id")
     * })
     */
   private $idPlan;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FechaCreacion", type="date")
     */
    private $fechaCreacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="FechaUpdate", type="date")
     */
    private $fechaUpdate;


 
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return Agencia_Plan
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaUpdate
     *
     * @param \DateTime $fechaUpdate
     *
     * @return Agencia_Plan
     */
    public function setFechaUpdate($fechaUpdate)
    {
        $this->fechaUpdate = $fechaUpdate;

        return $this;
    }

    /**
     * Get fechaUpdate
     *
     * @return \DateTime
     */
    public function getFechaUpdate()
    {
        return $this->fechaUpdate;
    }

    /**
     * Set idAgencia
     *
     * @param \Admin\AdminBundle\Entity\Agencia $idAgencia
     *
     * @return Agencia_Plan
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
     * Set idPlan
     *
     * @param \Admin\AdminBundle\Entity\Plan $idPlan
     *
     * @return Agencia_Plan
     */
    public function setIdPlan(\Admin\AdminBundle\Entity\Plan $idPlan = null)
    {
        $this->idPlan = $idPlan;

        return $this;
    }

    /**
     * Get idPlan
     *
     * @return \Admin\AdminBundle\Entity\Plan
     */
    public function getIdPlan()
    {
        return $this->idPlan;
    }
}
