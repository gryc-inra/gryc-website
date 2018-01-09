<?php
/**
 *    Copyright 2015-2018 Mathieu Piot
 *
 *    Licensed under the Apache License, Version 2.0 (the "License");
 *    you may not use this file except in compliance with the License.
 *    You may obtain a copy of the License at
 *
 *        http://www.apache.org/licenses/LICENSE-2.0
 *
 *    Unless required by applicable law or agreed to in writing, software
 *    distributed under the License is distributed on an "AS IS" BASIS,
 *    WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *    See the License for the specific language governing permissions and
 *    limitations under the License.
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A general class inherit by other files.
 *
 * @ORM\Entity
 * @ORM\Table(name="file")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"flatfile" = "FlatFile", "blastfile" = "BlastFile"})
 */
abstract class File
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
     * Set temp path.
     *
     * @param $tempPath
     *
     * @return $this
     */
    public function setTempPath($tempPath)
    {
        $this->tempPath = $tempPath;

        return $this;
    }

    /**
     * Get temp path.
     *
     * @return string
     */
    public function getTempPath()
    {
        return $this->tempPath;
    }

    /**
     * Get upload dir.
     *
     * Return the directory name where files are moved.
     *
     * @return string
     */
    abstract public function getStorageDir();
}
