<?php

namespace Admin\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Plan_Servicio
 *
 * @ORM\Table(name="Plan_Servicio", indexes={@ORM\Index(name="Id_Plan", columns={"id"}), @ORM\Index(name="Id_Servicio", columns={"Id_Servicio"})})
 * @ORM\Entity
 */
class PlanServicio
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
     * @var \Plan
     *
     * @ORM\ManyToOne(targetEntity="Plan",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Plan", referencedColumnName="id")
     * })
     */
    private $idPlan;

   /**
     * @var \Servicio
     *
     * @ORM\ManyToOne(targetEntity="Servicio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Servicio", referencedColumnName="id")
     * })
     */
    private $idServicio;

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
     * @return Plan_Servicio
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
     * @return Plan_Servicio
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
     * Set idPlan
     *
     * @param \Admin\AdminBundle\Entity\Plan $idPlan
     *
     * @return Plan_Servicio
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

    /**
     * Set idServicio
     *
     * @param \Admin\AdminBundle\Entity\Servicio $idServicio
     *
     * @return Plan_Servicio
     */
    public function setIdServicio(\Admin\AdminBundle\Entity\Servicio $idServicio = null)
    {
        $this->idServicio = $idServicio;

        return $this;
    }

    /**
     * Get idServicio
     *
     * @return \Admin\AdminBundle\Entity\Servicio
     */
    public function getIdServicio()
    {
        return $this->idServicio;
    }
}
