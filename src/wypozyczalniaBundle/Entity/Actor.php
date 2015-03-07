<?php
namespace wypozyczalniaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="actor")
 */
class Actor
{
	    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    protected $name;
	
	/**
     * @ORM\Column(type="integer")
     */
    protected $idMovie;
	

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Actor
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set idMovie
     *
     * @param integer $idMovie
     * @return Actor
     */
    public function setIdMovie($idMovie)
    {
        $this->idMovie = $idMovie;

        return $this;
    }

    /**
     * Get idMovie
     *
     * @return integer 
     */
    public function getIdMovie()
    {
        return $this->idMovie;
    }
}
