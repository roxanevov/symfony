<?php
/**
 * Created by PhpStorm.
 * User: digital
 * Date: 05/02/2018
 * Time: 16:24
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Entity\ShowRepository")
 * @ORM\Table(name="s_show")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Show
{
    const DATA_SOURCE_OMBD = 'OMBD';
    const DATA_SOURCE_DB = 'In local database';



    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Please entrer a name", groups={"create"})
     *
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank (groups={"create","update"})
     *
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
    private $abstract;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank (groups={"create", "update"})
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
    private $country;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="shows")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     *
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
    private $author;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank (groups={"create", "update"})
     *
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
    private $releasedDate;

    /**
     * @ORM\Column(type="string")
     * @Assert\Image(minHeight=300, minWidth=750, groups={"create"})
     * @JMS\Groups({"show"})
     */
    private $mainPicture;

    /**
     * @ORM\ManyToOne(targetEntity="Category")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * @Assert\NotBlank (groups={"create", "update"})
     *
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
    private $category;

    private  $tmpPicture;

    /**
     * @ORM\Column(options = {"default"="In local database"})
     *
     * @JMS\Expose
     * @JMS\Groups({"show"})
     */
    private $dataSource;

    /**
     * @return mixed
     */
    public function getTmpPicture()
    {
        return $this->tmpPicture;
    }

    /**
     * @param mixed $tmpPicture
     */
    public function setTmpPicture($tmpPicture)
    {
        $this->tmpPicture = $tmpPicture;
        return $this;
    }

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
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAbstract()
    {
        return $this->abstract;
    }

    /**
     * @param mixed $abstract
     */
    public function setAbstract($abstract)
    {
        $this->abstract = $abstract;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getReleasedDate()
    {
        return $this->releasedDate;
    }

    /**
     * @param mixed $releasedDate
     */
    public function setReleasedDate(\DateTime $releasedDate)
    {
        $this->releasedDate = $releasedDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMainPicture()
    {
        return $this->mainPicture;
    }

    /**
     * @param mixed $mainPicture
     */
    public function setMainPicture($mainPicture)
    {
        $this->mainPicture = $mainPicture;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }

    /**
     * @param mixed $dataSource
     */
    public function setDataSource($dataSource)
    {
        $this->dataSource = $dataSource;
        return $this;
    }
}