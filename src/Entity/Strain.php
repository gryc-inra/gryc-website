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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="strain")
 * @ORM\Entity(repositoryClass="App\Repository\StrainRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Strain
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(name="synonymes", type="array")
     */
    private $synonymes;

    /**
     * @ORM\Column(name="length", type="integer")
     */
    private $length;

    /**
     * @ORM\Column(name="gc", type="float")
     */
    private $gc;

    /**
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(name="cdsCount", type="integer")
     */
    private $cdsCount;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Chromosome", mappedBy="strain", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $chromosomes;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Species", inversedBy="strains")
     * @ORM\JoinColumn(nullable=false)
     */
    private $species;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Seo", mappedBy="strain", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $seos;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * @ORM\Column(name="public", type="boolean")
     */
    private $public = false;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", inversedBy="strains")
     */
    private $users;

    /**
     * @ORM\Column(name="typeStrain", type="boolean")
     */
    private $typeStrain = false;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Reference", mappedBy="strains")
     */
    private $references;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BlastFile", mappedBy="strain", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $blastFiles;

    public function __construct()
    {
        $this->synonymes = [];
        $this->chromosomes = new ArrayCollection();
        $this->seos = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->references = new ArrayCollection();
        $this->blastFiles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function addSynonym(string $synonym): self
    {
        if (!empty($synonym) && !\in_array($synonym, $this->synonymes, true)) {
            $this->synonymes[] = $synonym;
        }

        return $this;
    }

    public function removeSynonym(string $synonym): self
    {
        if (false !== $key = array_search($synonym, $this->synonymes, true)) {
            unset($this->synonymes[$key]);
            $this->synonymes = array_values($this->synonymes);
        }

        return $this;
    }

    public function emptySynonymes(): self
    {
        $this->synonymes = [];

        return $this;
    }

    public function setSynonymes(array $synonymes): self
    {
        $this->synonymes = [];

        foreach ($synonymes as $synonym) {
            $this->addSynonym($synonym);
        }

        return $this;
    }

    public function getSynonymes(): array
    {
        return $this->synonymes;
    }

    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function setGc(float $gc): self
    {
        $this->gc = $gc;

        return $this;
    }

    public function getGc(): ?float
    {
        return $this->gc;
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

    public function setCdsCount(int $cdsCount): self
    {
        $this->cdsCount = $cdsCount;

        return $this;
    }

    public function getCdsCount(): ?int
    {
        return $this->cdsCount;
    }

    public function addChromosome(Chromosome $chromosome): self
    {
        if (!$this->chromosomes->contains($chromosome)) {
            $this->chromosomes[] = $chromosome;
            $chromosome->setStrain($this);
        }

        return $this;
    }

    public function removeChromosome(Chromosome $chromosome): self
    {
        if ($this->chromosomes->contains($chromosome)) {
            $this->chromosomes->removeElement($chromosome);
        }

        return $this;
    }

    public function getChromosomes(): Collection
    {
        return $this->chromosomes;
    }

    public function setSpecies(Species $species): self
    {
        $this->species = $species;

        return $this;
    }

    public function getSpecies(): ?Species
    {
        return $this->species;
    }

    public function addSeo(Seo $seo): self
    {
        if (!$this->seos->contains($seo)) {
            $this->seos[] = $seo;
            $seo->setStrain($this);
        }

        return $this;
    }

    public function removeSeo(Seo $seo): self
    {
        if ($this->seos->contains($seo)) {
            $this->seos->removeElement($seo);
        }

        return $this;
    }

    public function getSeos(): Collection
    {
        return $this->seos;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setPublic(bool $bool): self
    {
        $this->public = $bool;

        return $this;
    }

    public function getPublic(): bool
    {
        return $this->public;
    }

    public function isPublic(): bool
    {
        return $this->public;
    }

    public function isPrivate(): bool
    {
        return !$this->isPublic();
    }

    public function isPublicToString(): string
    {
        if ($this->isPublic()) {
            return 'yes';
        }

        return 'no';
    }

    public function isPrivateToString(): string
    {
        if ($this->isPrivate()) {
            return 'yes';
        }

        return 'no';
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function getUsersId(): array
    {
        $usersId = [];

        foreach ($this->users as $user) {
            $usersId[] = $user->getId();
        }

        return $usersId;
    }

    public function isAllowedUser(User $user = null): bool
    {
        return $this->users->contains($user);
    }

    public function setTypeStrain(bool $bool): self
    {
        $this->typeStrain = $bool;

        return $this;
    }

    public function getTypeStrain(): bool
    {
        return $this->typeStrain;
    }

    public function isTypeStrain(): bool
    {
        return $this->typeStrain;
    }

    public function isTypeStrainToString(): string
    {
        if ($this->isTypeStrain()) {
            return 'yes';
        }

        return 'no';
    }

    public function addReference(Reference $reference): self
    {
        if (!$this->references->contains($reference)) {
            $this->references->add($reference);
        }

        return $this;
    }

    public function removeReference(Reference $reference): self
    {
        if ($this->references->contains($reference)) {
            $this->references->removeElement($reference);
        }

        return $this;
    }

    public function getReferences(): Collection
    {
        return $this->references;
    }

    public function addBlastFile(BlastFile $blastFile): self
    {
        if (!$this->blastFiles->contains($blastFile)) {
            $this->blastFiles[] = $blastFile;
            $blastFile->setStrain($this);
        }

        return $this;
    }

    public function removeBlastFile(BlastFile $blastFile): self
    {
        if ($this->blastFiles->contains($blastFile)) {
            $this->blastFiles->removeElement($blastFile);
        }

        return $this;
    }

    public function getBlastFiles(): Collection
    {
        return $this->blastFiles;
    }
}
