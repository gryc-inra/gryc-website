<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="first_name", type="string", length=255)
     * @Assert\NotBlank(message="Please enter your first name.", groups={"Registration", "Profile"})
     */
    private $firstName;

    /**
     * @ORM\Column(name="last_name", type="string", length=255)
     * @Assert\NotBlank(message="Please enter your last name.", groups={"Registration", "Profile"})
     */
    private $lastName;

    /**
     * @ORM\Column(name="company", type="string", length=255)
     * @Assert\NotBlank(message="Please enter your company's name.", groups={"Registration", "Profile"})
     */
    private $company;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Strain", inversedBy="authorizedUsers")
     */
    private $authorizedStrains;

    public function __construct()
    {
        parent::__construct();
        $this->authorizedStrains = new ArrayCollection();
    }

    /**
     * Set firstName.
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastNAme.
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set company.
     *
     * @param string $company
     *
     * @return User
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company.
     *
     * @return string
     */
    public function getCompany()
    {
        return $this->company;
    }

    public function addAuthorizedStrain(Strain $strain)
    {
        $this->authorizedStrains[] = $strain;

        return $this;
    }

    public function removeAuthorizedStrain(Strain $strain)
    {
        $this->authorizedStrains->removeElement($strain);
    }

    public function getAuthorizedStrains()
    {
        return $this->authorizedStrains;
    }
}
