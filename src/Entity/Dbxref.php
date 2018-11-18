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
 * Dbxref.
 *
 * @ORM\Table(name="dbxref")
 * @ORM\Entity(repositoryClass="App\Repository\DbxrefRepository")
 */
class Dbxref
{
    /**
     * The ID in the database.
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * The name of the dbxref.
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * The description.
     *
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * The pattern of the DBXREF.
     *
     * @var string
     *
     * @ORM\Column(name="pattern", type="string", length=255, unique=true)
     */
    private $pattern;

    /**
     * The URL.
     *
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * The source.
     *
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=255)
     */
    private $source;

    /**
     * Get id.
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     */
    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set description.
     *
     * @param string $description
     */
    public function setDescription($description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set pattern.
     *
     * @param string $pattern
     */
    public function setPattern($pattern): self
    {
        $this->pattern = $pattern;

        return $this;
    }

    /**
     * Get pattern.
     */
    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * Set url.
     *
     * @param string $url
     */
    public function setUrl($url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Set source.
     *
     * @param string $source
     */
    public function setSource($source): self
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source.
     */
    public function getSource(): string
    {
        return $this->source;
    }
}
