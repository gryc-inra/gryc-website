<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * The user entity.
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * The ID in the database.
     * 
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * The first name of the user.
     * 
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     * @Assert\NotBlank(message="Please enter your first name.", groups={"Registration", "Profile"})
     */
    private $firstName;

    /**
     * The last name of the user.
     * 
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     * @Assert\NotBlank(message="Please enter your last name.", groups={"Registration", "Profile"})
     */
    private $lastName;

    /**
     * The company of the user.
     * 
     * @var string
     *
     * @ORM\Column(name="company", type="string", length=255)
     * @Assert\NotBlank(message="Please enter your company's name.", groups={"Registration", "Profile"})
     */
    private $company;

    /**
     * The authorized strains for this user.
     * 
     * @var Strain|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Strain", inversedBy="authorizedUsers")
     */
    private $authorizedStrains;

    /**
     * User constructor.
     */
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

    /**
     * Add an authorized strain.
     *
     * @param Strain $strain
     * 
     * @return $this
     */
    public function addAuthorizedStrain(Strain $strain)
    {
        $this->authorizedStrains[] = $strain;

        return $this;
    }

    /**
     * Remove an authorized strain.
     *
     * @param Strain $strain
     */
    public function removeAuthorizedStrain(Strain $strain)
    {
        $this->authorizedStrains->removeElement($strain);
    }

    /**
     * Get authorized strains.
     *
     * @return Strain|ArrayCollection
     */
    public function getAuthorizedStrains()
    {
        return $this->authorizedStrains;
    }
}
