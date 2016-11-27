<?php

namespace Admin\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AgenciaUsuario
 *
 * @ORM\Table(name="usuario_hojadevida", indexes={@ORM\Index(name="Id_Usuario", columns={"Id_Usuario"}), @ORM\Index(name="Id_Hojadevida", columns={"id"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Admin\AdminBundle\Repository\UsuarioHojadevidaRepository")
 */
class UsuarioHojadevida
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
     * @var \Usuario
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="Id_Usuario", referencedColumnName="id")
     * })
     */
    private $idUsuario;

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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idUsuario
     *
     * @param \Admin\AdminBundle\Entity\User $idUsuario
     *
     * @return UsuarioHojadevida
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
     * Set idHojadevida
     *
     * @param \Admin\AdminBundle\Entity\Hojadevida $idHojadevida
     *
     * @return UsuarioHojadevida
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
