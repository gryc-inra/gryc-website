<?php
/**
 *    Copyright 2015-2018 Mathieu Piot.
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

namespace AppBundle\Service;

use AppBundle\Entity\BlastFile;
use AppBundle\Entity\File;
use Symfony\Component\Filesystem\Filesystem;

class FileManager
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var TokenGenerator
     */
    private $tokenGenerator;

    /**
     * @var string
     */
    private $projectDir;

    /**
     * FileManager constructor.
     *
     * @param Filesystem     $filesystem
     * @param TokenGenerator $tokenGenerator
     * @param string         $projectDir
     */
    public function __construct(Filesystem $filesystem, TokenGenerator $tokenGenerator, $projectDir)
    {
        $this->filesystem = $filesystem;
        $this->tokenGenerator = $tokenGenerator;
        $this->projectDir = $projectDir;
    }

    /**
     * Prepare file to be moved.
     *
     * @param File $file
     */
    public function prepareMoveFile(File $file)
    {
        // Create a \Symfony\Component\HttpFoundation\File\File with File
        // Return an error if file doesn't exists, and permit use ->getExtension()
        $httpFile = new \Symfony\Component\HttpFoundation\File\File($file->getFileSystemPath(), true);

        // 2 possibilities for FileName
        // 1. common files, just a token and the extension
        // 2. blastFiles, need to keep the old name as suffix (replace spaces by _)
        if ($file instanceof BlastFile) {
            $fileName = mb_strtolower(str_replace(' ', '_', $file->getStrain()->getName())).'_'.$httpFile->getFilename();
        } else {
            $fileName = $this->tokenGenerator->generateToken().'.'.$httpFile->getExtension();
        }

        $file->setPath($fileName);
    }

    /**
     * Move the file.
     *
     * @param File $file
     */
    public function moveFile(File $file)
    {
        // Prepare the full storage path
        $storageDir = $this->projectDir.'/files/'.$file->getStorageDir();

        // If there is a previous file (in case of replacement)
        if (null !== $file->getTempPath()) {
            $oldFilePath = $storageDir.'/'.$file->getTempPath();

            // If the old file exists
            if ($this->filesystem->exists($oldFilePath)) {
                $this->filesystem->remove($oldFilePath);
            }
        }

        // Then, copy the file
        $this->filesystem->copy($file->getFileSystemPath(), $storageDir.'/'.$file->getPath());
    }

    /**
     * Prepare file to be removed.
     *
     * @param File $file
     */
    public function prepareRemoveFile(File $file)
    {
        // Prepare the full storage path
        $storageDir = $this->projectDir.'/files/'.$file->getStorageDir();

        $file->setTempPath($storageDir.'/'.$file->getPath());
    }

    /**
     * Remove the file.
     *
     * @param File $file
     */
    public function removeFile(File $file)
    {
        if ($this->filesystem->exists($file->getTempPath())) {
            $this->filesystem->remove($file->getTempPath());
        }
    }

    /**
     * Get absolute path of a file.
     *
     * @param File $file
     *
     * @return string
     */
    public function getAbsolutePath(File $file)
    {
        // Prepare the full storage path
        $storageDir = $this->projectDir.'/files/'.$file->getStorageDir();

        return $storageDir.'/'.$file->getPath();
    }

    /**
     * Get the sendFile location of a file.
     *
     * @param File $file
     *
     * @return string
     */
    public function getSendFilePath(File $file)
    {
        return '/protected-files/'.$file->getStorageDir().'/'.$file->getPath();
    }
}
