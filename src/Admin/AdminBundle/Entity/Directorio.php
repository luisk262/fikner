<?php

namespace Admin\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Directorio
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Admin\AdminBundle\Repository\DirectorioRepository")
 */
class Directorio
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
     * @ORM\Column(name="nombre", type="string", length=100)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=255, nullable=true)
     */
    private $telefono;

    /**
     * @var string
     *
     * @ORM\Column(name="celular", type="string", length=255, nullable=true)
     */
    private $celular;

    /**
     * @var string
     *
     * @ORM\Column(name="direccion", type="string", length=255, nullable=true)
     */
    private $direccion;
    /**
     * @var string
     *
     * @ORM\Column(name="pais", type="string", length=100, nullable=true)
     */
    private $pais;
    /**
     * @var string
     *
     * @ORM\Column(name="ciudad", type="string", length=100, nullable=true)
     */
    private $ciudad;
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="categorias", type="string", length=255, nullable=true)
     */
    private $categorias;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=255, nullable=true)
     */
    private $descripcion;
     /**
     * @var string
     *
     * @ORM\Column(name="paginaweb", type="string", length=255, nullable=true)
     */
    private $paginaweb;


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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Directorio
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    
        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return Directorio
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    
        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set celular
     *
     * @param string $celular
     *
     * @return Directorio
     */
    public function setCelular($celular)
    {
        $this->celular = $celular;
    
        return $this;
    }

    /**
     * Get celular
     *
     * @return string
     */
    public function getCelular()
    {
        return $this->celular;
    }

    /**
     * Set direccion
     *
     * @param string $direccion
     *
     * @return Directorio
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    
        return $this;
    }

    /**
     * Get direccion
     *
     * @return string
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Directorio
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set categorias
     *
     * @param string $categorias
     *
     * @return Directorio
     */
    public function setCategorias($categorias)
    {
        $this->categorias = $categorias;
    
        return $this;
    }

    /**
     * Get categorias
     *
     * @return string
     */
    public function getCategorias()
    {
        return $this->categorias;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Directorio
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    
        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set paginaweb
     *
     * @param string $paginaweb
     *
     * @return Directorio
     */
    public function setPaginaweb($paginaweb)
    {
        $this->paginaweb = $paginaweb;
    
        return $this;
    }

    /**
     * Get paginaweb
     *
     * @return string
     */
    public function getPaginaweb()
    {
        return $this->paginaweb;
    }
}
