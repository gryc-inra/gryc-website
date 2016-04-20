<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ContactUs.
 *
 * @ORM\Table(name="contact_us")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContactUsRepository")
 */
class ContactUs
{
    /**
     * Id in the database.
     *
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * The user first name.
     *
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     * @Assert\Length(min=3)
     */
    private $firstName;

    /**
     * The user last name.
     *
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     * @Assert\Length(min=3)
     */
    private $lastName;

    /**
     * The user email.
     *
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     * @Assert\Email(checkMX = true)
     */
    private $email;

    /**
     * The message subject.
     *
     * @var string
     *
     * @ORM\Column(name="subject", type="string", length=255)
     * @Assert\Length(min=3)
     */
    private $subject;

    /**
     * The message content.
     *
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     * @Assert\Length(min=20)
     */
    private $message;

    /**
     * The question date.
     *
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime()
     */
    private $date;

    /**
     * The category.
     *
     * @var ContactUsCategory
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ContactUsCategory")
     */
    private $category;

    /**
     * ContactUs constructor.
     */
    public function __construct()
    {
        $this->date = new \DateTime();
    }

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
     * Set firstName.
     *
     * @param string $firstName
     *
     * @return ContactUs
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
     * Set lastName.
     *
     * @param string $lastName
     *
     * @return ContactUs
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
     * Set email.
     *
     * @param string $email
     *
     * @return ContactUs
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set subject.
     *
     * @param string $subject
     *
     * @return ContactUs
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Get subject.
     *
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set message.
     *
     * @param string $message
     *
     * @return ContactUs
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set date.
     *
     * @param \DateTime $date
     *
     * @return ContactUs
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set category.
     *
     * @param ContactUsCategory $category
     *
     * @return ContactUs
     */
    public function setCategory(ContactUsCategory $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return ContactUsCategory
     */
    public function getCategory()
    {
        return $this->category;
    }
}
