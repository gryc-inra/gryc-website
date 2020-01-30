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
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\MappedSuperclass
 */
class QueuingEntitySuperclass
{
    /**
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(name="command_line", type="text", nullable=true)
     */
    private $commandLine;

    /**
     * @ORM\Column(name="output", type="text", nullable=true)
     */
    private $output;

    /**
     * @ORM\Column(name="error_output", type="text", nullable=true)
     */
    private $errorOutput;

    /**
     * @ORM\Column(name="exit_code", type="integer", nullable=true)
     */
    private $exitCode;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */
    private $createdBy;

    public function __construct()
    {
        $this->status = 'pending';
    }

    public function __clone()
    {
        $this->name = null;
        $this->status = 'pending';
        $this->commandLine = null;
        $this->output = null;
        $this->errorOutput = null;
        $this->exitCode = null;
        $this->created = null;
        $this->createdBy = null;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setCommandLine(?string $commandLine): self
    {
        $this->commandLine = $commandLine;

        return $this;
    }

    public function getCommandLine(): ?string
    {
        return $this->commandLine;
    }

    public function setOutput(?string $output): self
    {
        $this->output = $output;

        return $this;
    }

    public function getOutput(): ?string
    {
        return $this->output;
    }

    public function setErrorOutput(?string $errorOutput): self
    {
        $this->errorOutput = $errorOutput;

        return $this;
    }

    public function getErrorOutput(): ?string
    {
        return $this->errorOutput;
    }

    public function setExitCode(?int $exitCode): self
    {
        $this->exitCode = $exitCode;

        return $this;
    }

    public function getExitCode(): ?int
    {
        return $this->exitCode;
    }

    public function getCreated(): ?\DateTime
    {
        return $this->created;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }
}
