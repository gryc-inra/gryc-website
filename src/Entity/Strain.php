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
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Strain.
 *
 * @ORM\Table(name="strain")
 * @ORM\Entity(repositoryClass="App\Repository\StrainRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Strain
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
     * The name of the strain.
     *
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * An array of synonymes.
     *
     * @var array
     *
     * @ORM\Column(name="synonymes", type="array")
     */
    private $synonymes;

    /**
     * The length of the strain. (Total of chromosomes length).
     *
     * @var int
     *
     * @ORM\Column(name="length", type="integer")
     */
    private $length;

    /**
     * The G/C percentage.
     *
     * @var float
     *
     * @ORM\Column(name="gc", type="float")
     */
    private $gc;

    /**
     * The status of the strain.
     * Eg: complete.
     *
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * The number of CDS.
     *
     * @var int
     *
     * @ORM\Column(name="cdsCount", type="integer")
     */
    private $cdsCount;

    /**
     * The owned chromosomes.
     *
     * @var Chromosome|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Chromosome", mappedBy="strain", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $chromosomes;

    /**
     * The parent species.
     *
     * @var Species
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Species", inversedBy="strains")
     * @ORM\JoinColumn(nullable=false)
     */
    private $species;

    /**
     * The Seo linked on the species.
     *
     * @var Seo|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Seo", mappedBy="strain", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $seos;

    /**
     * The slug, for url.
     *
     * @var string
     *
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(name="slug", type="string", length=128, unique=true)
     */
    private $slug;

    /**
     * Is the strain public ?
     * Eg: true (public) or false (private).
     *
     * @var bool
     *
     * @ORM\Column(name="public", type="boolean")
     */
    private $public = false;

    /**
     * The users for this strain.
     *
     * @var User|ArrayCollection
     *
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
     * Blast.
     *
     * @var BlastFile|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\BlastFile", mappedBy="strain", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $blastFiles;

    /**
     * Strain constructor.
     */
    public function __construct()
    {
        $this->synonymes = [];
        $this->chromosomes = new ArrayCollection();
        $this->seos = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->references = new ArrayCollection();
        $this->blastFiles = new ArrayCollection();
    }

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
     * Add synonym.
     *
     * @param string $synonym
     */
    public function addSynonym($synonym): self
    {
        if (!empty($synonym) && !\in_array($synonym, $this->synonymes, true)) {
            $this->synonymes[] = $synonym;
        }

        return $this;
    }

    /**
     * Remove synonym.
     *
     * @param string $synonym
     */
    public function removeSynonym($synonym): self
    {
        if (false !== $key = array_search($synonym, $this->synonymes, true)) {
            unset($this->synonymes[$key]);
            $this->synonymes = array_values($this->synonymes);
        }

        return $this;
    }

    /**
     * Empty synonymes.
     */
    public function emptySynonymes(): self
    {
        $this->synonymes = [];

        return $this;
    }

    /**
     * Set synonymes.
     *
     * @param array $synonymes
     */
    public function setSynonymes($synonymes): self
    {
        foreach ($synonymes as $synonym) {
            $this->addSynonym($synonym);
        }

        return $this;
    }

    /**
     * Get synonymes.
     */
    public function getSynonymes(): array
    {
        return $this->synonymes;
    }

    /**
     * Set length.
     *
     * @param int $length
     */
    public function setLength($length): self
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Get length.
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * Set gc.
     *
     * @param float $gc
     */
    public function setGc($gc): self
    {
        $this->gc = $gc;

        return $this;
    }

    /**
     * Get gc.
     */
    public function getGc(): float
    {
        return $this->gc;
    }

    /**
     * Set status.
     *
     * @param string $status
     */
    public function setStatus($status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set cdsCount.
     *
     * @param int $cdsCount
     */
    public function setCdsCount($cdsCount): self
    {
        $this->cdsCount = $cdsCount;

        return $this;
    }

    /**
     * Get cdsCount.
     */
    public function getCdsCount(): int
    {
        return $this->cdsCount;
    }

    /**
     * Add chromosomes.
     *
     *
     * @return $this
     */
    public function addChromosome(Chromosome $chromosome)
    {
        if (!$this->chromosomes->contains($chromosome)) {
            $this->chromosomes[] = $chromosome;
            $chromosome->setStrain($this);
        }

        return $this;
    }

    /**
     * Remove chromosomes.
     *
     *
     * @return $this
     */
    public function removeChromosome(Chromosome $chromosome)
    {
        if ($this->chromosomes->contains($chromosome)) {
            $this->chromosomes->removeElement($chromosome);
        }

        return $this;
    }

    /**
     * Get chromosomes.
     *
     * @return Chromosome|ArrayCollection
     */
    public function getChromosomes()
    {
        return $this->chromosomes;
    }

    /**
     * Set species.
     *
     *
     * @return $this
     */
    public function setSpecies(Species $species)
    {
        $this->species = $species;

        return $this;
    }

    /**
     * Get species.
     */
    public function getSpecies(): Species
    {
        return $this->species;
    }

    /**
     * Add Seo.
     */
    public function addSeo(Seo $seo): self
    {
        if (!$this->seos->contains($seo)) {
            $this->seos[] = $seo;
            $seo->setStrain($this);
        }

        return $this;
    }

    /**
     * Remove Seo.
     */
    public function removeSeo(Seo $seo)
    {
        if ($this->seos->contains($seo)) {
            $this->seos->removeElement($seo);
        }

        return $this;
    }

    /**
     * Get Seo.
     *
     * @return Seo|ArrayCollection
     */
    public function getSeos()
    {
        return $this->seos;
    }

    /**
     * Set slug.
     *
     * @param string $slug
     */
    public function setSlug($slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Set public.
     *
     * @param bool $bool
     *
     * @return $this
     */
    public function setPublic($bool)
    {
        $this->public = $bool;

        return $this;
    }

    /**
     * Get public.
     */
    public function getPublic(): bool
    {
        return $this->public;
    }

    /**
     * Is public?
     */
    public function isPublic(): bool
    {
        return $this->public;
    }

    /**
     * Is private?
     */
    public function isPrivate(): bool
    {
        return !$this->isPublic();
    }

    /**
     * Return if the strain is public or no, in letter.
     */
    public function isPublicToString(): string
    {
        if ($this->isPublic()) {
            return 'yes';
        }

        return 'no';
    }

    /**
     * Return if the strain is private or no, in letter.
     */
    public function isPrivateToString(): string
    {
        if ($this->isPrivate()) {
            return 'yes';
        }

        return 'no';
    }

    /**
     * Add user.
     *
     *
     * @return $this
     */
    public function addUser(User $user)
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    /**
     * Remove user.
     *
     *
     * @return $this
     */
    public function removeUser(User $user)
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }

    /**
     * Get users.
     *
     * @return User|ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Get users id.
     */
    public function getUsersId(): array
    {
        $usersId = [];

        foreach ($this->users as $user) {
            $usersId[] = $user->getId();
        }

        return $usersId;
    }

    /**
     * Is allowed user ?
     *
     * @param User $user
     */
    public function isAllowedUser(User $user = null): bool
    {
        return $this->users->contains($user);
    }

    /**
     * Set type.
     *
     * @param bool $bool
     *
     * @return $this
     */
    public function setTypeStrain($bool)
    {
        $this->typeStrain = $bool;

        return $this;
    }

    /**
     * Get type.
     */
    public function getTypeStrain(): bool
    {
        return $this->typeStrain;
    }

    /**
     * Is a type strain?
     */
    public function isTypeStrain(): bool
    {
        return $this->typeStrain;
    }

    /**
     * Return if the strain is a type strain or not, in letter.
     */
    public function isTypeStrainToString(): string
    {
        if ($this->isTypeStrain()) {
            return 'yes';
        }

        return 'no';
    }

    public function addReference(Reference $reference)
    {
        if (!$this->references->contains($reference)) {
            $this->references->add($reference);
        }

        return $this;
    }

    public function removeReference(Reference $reference)
    {
        if ($this->references->contains($reference)) {
            $this->references->removeElement($reference);
        }

        return $this;
    }

    public function getReferences()
    {
        return $this->references;
    }

    /**
     * Add BlastFile.
     *
     *
     * @return $this
     */
    public function addBlastFile(BlastFile $blastFile)
    {
        if (!$this->blastFiles->contains($blastFile)) {
            $this->blastFiles[] = $blastFile;
            $blastFile->setStrain($this);
        }

        return $this;
    }

    /**
     * Remove BlastFile.
     *
     *
     * @return $this
     */
    public function removeBlastFile(BlastFile $blastFile)
    {
        if ($this->blastFiles->contains($blastFile)) {
            $this->blastFiles->removeElement($blastFile);
        }

        return $this;
    }

    /**
     * Get BlastFiles.
     *
     * @return BlastFile|ArrayCollection
     */
    public function getBlastFiles()
    {
        return $this->blastFiles;
    }
}
