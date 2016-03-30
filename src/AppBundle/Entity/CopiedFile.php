<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @ORM\Entity
 * @ORM\Table(name="copiedFile")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"copiedFile" = "CopiedFile", "flatfile" = "FlatFile"})
 * @ORM\HasLifecycleCallbacks
 */
class CopiedFile
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="path", type="string", length=255, unique=true)
     */
    private $path;

    private $fileSystemPath;

    private $tempPath;

    private $fs;

    public function __construct()
    {
        $this->fs = new Filesystem();
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set path.
     *
     * @param string $path
     *
     * @return File
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    public function setFileSystemPath($fileSystemPath)
    {
        $this->fileSystemPath = $fileSystemPath;

        // Vérifier si l'entité avait déjà un fichier attaché
        if (null !== $this->path) {
            // On stocker le path de l'ancien fichier pour le supprimer plus tard
            $this->tempPath = $this->path;

            // On réinitialise le path
            $this->path = null;
        }
    }

    /**
     * Get file.
     */
    public function getFileSystemPath()
    {
        return $this->fileSystemPath;
    }

    /**
     * Get absolute path.
     *
     * @return null|string
     */
    public function getAbsolutePath()
    {
        return null === $this->path ? null : $this->getUploadRootDir().'/'.$this->path;
    }

    /**
     * Get upload root dir.
     *
     * @return string
     */
    protected function getUploadRootDir()
    {
        // le chemin absolu du répertoire où les documents uploadés doivent être sauvegardés
        return __DIR__.'/../../../protected-files/'.$this->getUploadDir();
    }

    /**
     * Get upload dir.
     *
     * @return string
     */
    protected function getUploadDir()
    {
        // on se débarrasse de « __DIR__ » afin de ne pas avoir de problème lorsqu'on affiche
        // le document/image dans la vue.
        return 'copiedFiles';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null === $this->fileSystemPath) {
            return;
        }

        $this->path = uniqid('', true).'.'.pathinfo($this->fileSystemPath, PATHINFO_EXTENSION);
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->fileSystemPath) {
            return;
        }

        if (null !== $this->tempPath) {
            $oldFile = $this->getUploadRootDir().'/'.$this->tempPath;

            if (file_exists($oldFile)) {
                unlink($oldFile);
            }
        }

        $this->fs->copy($this->fileSystemPath, $this->getUploadRootDir().'/'.$this->path);
    }

    /**
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        $this->tempPath = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (file_exists($this->tempPath)) {
            unlink($this->tempPath);
        }
    }
}
