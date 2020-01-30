<?php

/*
 * Copyright 2015-2018 Mathieu Piot.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="file")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"flatfile" = "FlatFile", "blastfile" = "BlastFile"})
 */
abstract class File
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setFileSystemPath(string $fileSystemPath): self
    {
        $this->fileSystemPath = $fileSystemPath;

        // Vérifier si l'entité avait déjà un fichier attaché
        if (null !== $this->path) {
            // On stocker le path de l'ancien fichier pour le supprimer plus tard
            $this->tempPath = $this->path;

            // On réinitialise le path
            $this->path = null;
        }

        return $this;
    }

    public function getFileSystemPath(): ?string
    {
        return $this->fileSystemPath;
    }

    public function setTempPath(?string $tempPath): self
    {
        $this->tempPath = $tempPath;

        return $this;
    }

    public function getTempPath(): ?string
    {
        return $this->tempPath;
    }

    abstract public function getStorageDir(): ?string;
}
