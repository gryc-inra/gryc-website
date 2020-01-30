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

// src/App/Entity/User.php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email")
 */
class User implements AdvancedUserInterface, \Serializable
{
    const ROLE_DEFAULT = 'ROLE_USER';
    const NUM_ITEMS = 10;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @Assert\Length(max=4096)
     * @Assert\Regex("/[a-z]/", message="Your password must contain at least one lowercase letter.")
     * @Assert\Regex("/[A-Z]/", message="Your password must contain at least one uppercase letter.")
     * @Assert\Regex("/[\d]/", message="Your password must contain at least one number.")
     * @Assert\Length(min=8, max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(name="roles", type="array")
     */
    private $roles;

    /**
     * @ORM\Column(name="enabled", type="boolean")
     */
    private $enabled;

    /**
     * @ORM\Column(name="confirmation_token", type="string", nullable=true)
     */
    private $confirmationToken;

    /**
     * @ORM\Column(name="first_name", type="string", length=255)
     * @Assert\NotBlank(message="Please enter your first name.")
     * @Assert\Length(
     *     min=2,
     *     max=255,
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     * )
     */
    private $firstName;

    /**
     * @ORM\Column(name="last_name", type="string", length=255)
     * @Assert\NotBlank(message="Please enter your last name.")
     * @Assert\Length(
     *     min=2,
     *     max=255,
     *     minMessage="The name is too short.",
     *     maxMessage="The name is too long.",
     * )
     */
    private $lastName;

    /**
     * The company of the user.
     *
     * @var string
     *
     * @ORM\Column(name="company", type="string", length=255)
     * @Assert\NotBlank(message="Please enter your company's name.")
     */
    private $company;

    /**
     * The strains for this user.
     *
     * @var Strain|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Strain", mappedBy="users")
     */
    private $strains;

    /**
     * @ORM\Column(name="session_id", type="string", length=255, nullable=true)
     */
    private $sessionId;

    public function __construct()
    {
        $this->roles = [];
        $this->enabled = false;
        $this->strains = new ArrayCollection();
    }

    /**
     * Get ID.
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set email.
     *
     * @param $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Get username.
     */
    public function getUsername(): ?string
    {
        return $this->email;
    }

    /**
     * Set plain password.
     *
     * @param $password
     */
    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * Get plain password.
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * Set password.
     *
     * @param $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password.
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Get salt.
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Set roles.
     *
     * @param $array
     *
     * @return $this
     */
    public function setRoles($array)
    {
        $this->roles = $array;

        return $this;
    }

    /**
     * Add role.
     *
     * @param $role
     *
     * @return $this
     */
    public function addRole($role)
    {
        $role = mb_strtoupper($role);
        if ($role === static::ROLE_DEFAULT) {
            return $this;
        }

        if (!\in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * Remove role.
     *
     * @param $role
     *
     * @return $this
     */
    public function removeRole($role)
    {
        if (false !== $key = array_search(mb_strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * Has role.
     *
     * @param $role
     */
    public function hasRole($role): bool
    {
        if (false === array_search(mb_strtoupper($role), $this->roles, true)) {
            return false;
        }

        return true;
    }

    /**
     * Get roles.
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = static::ROLE_DEFAULT;

        return array_unique($roles);
    }

    /**
     * Set enabled.
     *
     * @param bool $enabled
     */
    public function setEnabled($enabled): self
    {
        $this->enabled = $enabled;
        $this->confirmationToken = null;

        return $this;
    }

    /**
     * Set confirmation token.
     *
     * @param string $confirmationToken
     */
    public function setConfirmationToken($confirmationToken): self
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    /**
     * Get confirmation token.
     */
    public function getConfirmationToken(): string
    {
        return $this->confirmationToken;
    }

    /**
     * Erase credentials.
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * Is account non expired ?
     */
    public function isAccountNonExpired(): bool
    {
        return true;
    }

    /**
     * Is account non locked ?
     */
    public function isAccountNonLocked(): bool
    {
        return true;
    }

    /**
     * Is credential non expired ?
     */
    public function isCredentialsNonExpired(): bool
    {
        return true;
    }

    /**
     * Is enabled ?
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * Serialize.
     */
    public function serialize(): string
    {
        return serialize([
            $this->id,
            $this->email,
            $this->password,
            $this->enabled,
        ]);
    }

    /**
     * Unserialize.
     *
     * @param string $serialized
     */
    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->password,
            $this->enabled
        ) = unserialize($serialized);
    }

    /**
     * Set firstName.
     *
     * @param $firstName
     *
     * @return $this
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName.
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName.
     *
     *
     * @return $this
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName.
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Get fullName.
     */
    public function getFullName(): string
    {
        return $this->firstName.' '.$this->lastName;
    }

    /**
     * Set company.
     *
     * @param string $company
     */
    public function setCompany($company): self
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company.
     */
    public function getCompany(): ?string
    {
        return $this->company;
    }

    /**
     * Add a strain.
     *
     *
     * @return $this
     */
    public function addStrain(Strain $strain)
    {
        if (!$this->strains->contains($strain)) {
            $strain->addUser($this);
            $this->strains[] = $strain;
        }

        return $this;
    }

    /**
     * Remove a strain.
     *
     *
     * @return $this
     */
    public function removeStrain(Strain $strain)
    {
        if ($this->strains->contains($strain)) {
            $strain->removeUser($this);
            $this->strains->removeElement($strain);
        }

        return $this;
    }

    /**
     * Get strains.
     *
     * @return Strain|ArrayCollection
     */
    public function getStrains()
    {
        return $this->strains;
    }

    public function setSessionId($sessionId)
    {
        $this->sessionId = $sessionId;

        return $this;
    }

    public function getSessionId()
    {
        return $this->sessionId;
    }
}
