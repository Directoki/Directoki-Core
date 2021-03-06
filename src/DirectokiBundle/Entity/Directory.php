<?php

namespace DirectokiBundle\Entity;



use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *  @license 3-clause BSD
 *  @link https://github.com/Directoki/Directoki-Core/blob/master/LICENSE.txt
 * @ORM\Entity()
 * @ORM\Table(name="directoki_directory", uniqueConstraints={@ORM\UniqueConstraint(name="directory_public_id", columns={"project_id", "public_id"})})
 * @ORM\HasLifecycleCallbacks
 */
class Directory
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;



    /**
     * @ORM\ManyToOne(targetEntity="DirectokiBundle\Entity\Project")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false)
     */
    private $project;


    /**
     * @ORM\Column(name="public_id", type="string", length=250, unique=false, nullable=false)
     * @Assert\NotBlank()
     */
    private $publicId;


    /**
     * @var string
     *
     * @ORM\Column(name="title_singular", type="string", length=250, nullable=false)
     */
    private $titleSingular;


    /**
     * @var string
     *
     * @ORM\Column(name="title_plural", type="string", length=250, nullable=false)
     */
    private $titlePlural;


    /**
     * @var \DateTime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;


    /**
     * @ORM\ManyToOne(targetEntity="DirectokiBundle\Entity\Event")
     * @ORM\JoinColumn(name="creation_event_id", referencedColumnName="id", nullable=false)
     */
    protected $creationEvent;


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param mixed $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * @return mixed
     */
    public function getPublicId()
    {
        return $this->publicId;
    }

    /**
     * @param mixed $publicId
     */
    public function setPublicId(string $publicId)
    {
        $this->publicId = $publicId;
    }


    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getCreationEvent() {
        return $this->creationEvent;
    }

    /**
     * @param mixed $creationEvent
     */
    public function setCreationEvent( $creationEvent ) {
        $this->creationEvent = $creationEvent;
    }

    /**
     * @return string
     */
    public function getTitlePlural() {
        return $this->titlePlural;
    }

    /**
     * @param string $titlePlural
     */
    public function setTitlePlural( string $titlePlural ) {
        $this->titlePlural = $titlePlural;
    }

    /**
     * @return mixed
     */
    public function getTitleSingular() {
        return $this->titleSingular;
    }

    /**
     * @param mixed $titleSingular
     */
    public function setTitleSingular( string $titleSingular ) {
        $this->titleSingular = $titleSingular;
    }






    /**
     * @ORM\PrePersist()
     */
    public function beforeFirstSave() {
        $this->createdAt = new \DateTime("", new \DateTimeZone("UTC"));
    }


}

