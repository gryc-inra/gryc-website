<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="multiple_alignment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MultipleAlignmentRepository")
 */
class MultipleAlignment
{
    const NB_KEPT_ALIGNMENT = 10;

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
     * @var string
     *
     * @ORM\Column(name="query", type="text")
     */
    private $query;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="output", type="text", nullable=true)
     */
    private $output;

    /**
     * @var string
     *
     * @ORM\Column(name="error_output", type="text", nullable=true)
     */
    private $errorOutput;

    /**
     * @var string
     *
     * @ORM\Column(name="exit_code", type="integer", nullable=true)
     */
    private $exitCode;

    /**
     * @var \DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var User
     *
     * @Gedmo\Blameable(on="create")
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     */
    private $createdBy;

    public function __construct()
    {
        $this->query = ">my-query\n";
        $this->status = 'pending';
    }

    public function __clone()
    {
        $this->id = null;
        $this->name = null;
        $this->status = 'pending';
        $this->output = null;
        $this->errorOutput = null;
        $this->exitCode = null;
        $this->created = null;
        $this->createdBy = null;
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

    public function setQuery($query)
    {
        $this->query = $query;

        return $this;
    }

    public function getQuery()
    {
        return $this->query;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Blast
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set status.
     *
     * @param string $status
     *
     * @return Blast
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set output.
     *
     * @param string $output
     *
     * @return Blast
     */
    public function setOutput($output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * Get output.
     *
     * @return string
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * Set error output.
     *
     * @param string $errorOutput
     *
     * @return Blast
     */
    public function setErrorOutput($errorOutput)
    {
        $this->errorOutput = $errorOutput;

        return $this;
    }

    /**
     * Get error output.
     *
     * @return string
     */
    public function getErrorOutput()
    {
        return $this->errorOutput;
    }

    /**
     * Set exit code.
     *
     * @param string $errorOutput
     *
     * @return Blast
     */
    public function setExitCode($exitCode)
    {
        $this->exitCode = $exitCode;

        return $this;
    }

    /**
     * Get exit code.
     *
     * @return string
     */
    public function getExitCode()
    {
        return $this->exitCode;
    }

    /**
     * Get created.
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Get created by.
     *
     * @return User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
}
