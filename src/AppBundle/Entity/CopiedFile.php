<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Filesystem\Filesystem;

/**
 * A general class inherit by other copied files.
 *
 * @ORM\Entity
 * @ORM\Table(name="copied_file")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"copiedFile" = "CopiedFile", "flatfile" = "FlatFile"})
 * @ORM\HasLifecycleCallbacks
 */
class CopiedFile
{
    /**
     * The ID in the database.
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * The path of the file on the server.
     *
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, unique=true)
     */
    private $path;

    /**
     * The path of the file on the system (befor import).
     *
     * @var string
     */
    private $fileSystemPath;

    /**
     * A temporaty path, before deletion.
     *
     * @var string
     */
    private $tempPath;

    /**
     * The filesystem object.
     *
     * @var Filesystem
     */
    private $fs;

    /**
     * CopiedFile constructor.
     */
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
     * @return string
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

    /**
     * Set fileSystemPath.
     *
     * @param string $fileSystemPath
     */
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
        return __DIR__.'/../../../files/'.$this->getUploadDir();
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
     * Before persist or update.
     *
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
     * After persist or update.
     *
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
     * Before remove.
     *
     * @ORM\PreRemove()
     */
    public function preRemoveUpload()
    {
        $this->tempPath = $this->getAbsolutePath();
    }

    /**
     * After remove.
     *
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (file_exists($this->tempPath)) {
            unlink($this->tempPath);
        }
    }
}
