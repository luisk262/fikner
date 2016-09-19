<?php

namespace Admin\AdminBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * File
 *
 * @ORM\Table(name="file")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class File
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
     * )
     */
    /**
     * @var string $image
     * @Assert\File(
     *     maxSize = "1024k",
     *     mimeTypes = {"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "Por favor carge un archivo pdf"
     * )
     * @ORM\Column(name="file", type="string", length=255, nullable=true)
     */
    public $file;
    private $filename;
    private $aux;
    
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
     * Set file
     *
     * @param string $file
     *
     * @return File
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }
    /**
     * Set file
     *
     * @param string $filename
     * @return file
     */
    public function setFileName($filename) {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFileName() {
        return $this->filename;
    }
    public function getFullFilePath() {
        return null === $this->file ? null : $this->getUploadRootDir() . $this->file;
        //retorna la imagen para mostrar en web
    }
    protected function getUploadRootDir() {
        //ruta del dicrectorio donde se van a guardar los archivos
       
        return $this->getTmpUploadRootDir()."/../" . $this->id . "/";
    }

    protected function getTmpUploadRootDir() {
        //la ruta del directorio absoluta donde se deben guardar los documentos cargados
        return __DIR__ . '/../../../../web/upload/Files/tmp/';
    }
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function uploadFile() {
        //validamos si es una actualizacion o una nueva
        //si las fehcas son diferentes es actualizacion de lo contrario es nuevo
        if ($this->fechaupdate != $this->fecha) {
            if ($this->file != null) {
                ///asignamos los nombres nuevos
                $tempFileName = time() . '_1.' . pathinfo((string) $this->file->getClientOriginalName(), PATHINFO_EXTENSION);
                //borramos imagenes viejas
                $dir = $this->getUploadRootDir();
                if (is_file($dir . $this->aux)) {
                    unlink($dir . $this->aux);
                }
                /// guardamos en el servidor los archivos
                $this->setFileName($this->file->getClientOriginalName());
                $this->file->move($this->getUploadRootDir(), $tempFileName);
                //grabamos los nombres de archivo en el servidor
                $this->setFile($tempFileName);
            } else {
                $this->setFile($this->aux);
            }
        } else {
            if (null === $this->file) {
                return;
            }

            $tempFileName = time() . '_1.' . pathinfo((string) $this->file->getClientOriginalName(), PATHINFO_EXTENSION);
            if (!$this->id) {
                $this->setFileName($this->file->getClientOriginalName());
                $this->file->move($this->getTmpUploadRootDir(), $this->file->getClientOriginalName());
            } else {
                $this->file->move($this->getUploadRootDir(), $tempFileName);
                unlink($this->getUploadRootDir() . $tempFileName);
            }
            $this->setFile($tempFileName);
        }
    }

    /**
     * 
     * @ORM\PreRemove()
     */
    public function removeFile() {
        unlink($this->getFullFilePath());
        rmdir($this->getUploadRootDir());
    }

    /**
     * @ORM\PostPersist()
     */
    public function moveFile() {
        if (null === $this->file) {
            return;
        }
        if (!is_dir($this->getUploadRootDir())) {
            mkdir($this->getUploadRootDir(), 0777, true);
        }
        copy($this->getTmpUploadRootDir() . $this->getFileName(), $this->getFullFilePath());
        unlink($this->getTmpUploadRootDir() . $this->getFileName());
    }


    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return File
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
     * @return File
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Hojadevida
     */
    public function setAux($aux) {
        $this->aux = $aux;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getAux() {
        return $this->aux;
    }

    
}
