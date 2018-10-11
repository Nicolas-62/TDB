<?php

namespace TDB\PasswordManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Service
 *
 * @ORM\Table(name="service")
 * @ORM\Entity(repositoryClass="TDB\PasswordManagerBundle\Repository\ServiceRepository")
 */
class Service
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dateCreation = new \Datetime();
        $this->dateModification = new \Datetime();
        $this->access = [];
    }
    /**
     * @ORM\ManyToOne(targetEntity="TDB\PasswordManagerBundle\Entity\Entree", inversedBy="services")
     * @ORM\JoinColumn(nullable=false)
     */
    private $entree;

    /**
     * @ORM\OneToMany(targetEntity="TDB\PasswordManagerBundle\Entity\Acces", mappedBy="service")
     */
    private $access;    
    /**
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
     * @ORM\Column(name="service_nom", type="string", length=255)
     */
    private $serviceNom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modification", type="datetime")
     */
    private $dateModification;

    /**
     * @var int
     *
     * @ORM\Column(name="entree_id", type="integer")
     */
    private $entreeId;

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
     * Set serviceNom.
     *
     * @param string $serviceNom
     *
     * @return Service
     */
    public function setServiceNom($serviceNom)
    {
        $this->serviceNom = $serviceNom;

        return $this;
    }

    /**
     * Get serviceNom.
     *
     * @return string
     */
    public function getServiceNom()
    {
        return $this->serviceNom;
    }

    /**
     * Set dateCreation.
     *
     * @param \DateTime $dateCreation
     *
     * @return Service
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation.
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set dateModification.
     *
     * @param \DateTime $dateModification
     *
     * @return Service
     */
    public function setDateModification($dateModification)
    {
        $this->dateModification = $dateModification;

        return $this;
    }

    /**
     * Get dateModification.
     *
     * @return \DateTime
     */
    public function getDateModification()
    {
        return $this->dateModification;
    }

    /**
     * Set entree.
     *
     * @param Entree $entree
     *
     * @return Service
     */
    public function setEntree(Entree $entree)
    {
        $this->entree = $entree;

        return $this;
    }

    /**
     * Get entree.
     *
     * @return Entree
     */
    public function getEntree()
    {
        return $this->entree;
    }

    /**
     * Add access.
     *
     * @param \TDB\PasswordManagerBundle\Entity\Acces $access
     *
     * @return Service
     */
    public function addAcces(\TDB\PasswordManagerBundle\Entity\Acces $acces)
    {
        $this->access[] = $acces;
        $acces->setService($this);

        return $this;
    }
    /**
     * Set access.
     *
     * @param array
     *
     * @return Service
     */
    public function setAccess($tab)
    {
        $this->access = $tab;
        return $this;
    }

    /**
     * Remove access.
     *
     * @param \TDB\PasswordManagerBundle\Entity\Acces $acces
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeAcces(\TDB\PasswordManagerBundle\Entity\Acces $acces)
    {
        return $this->access->removeElement($acces);
    }

    /**
     * Get access.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAccess()
    {
        return $this->access;
    }
    /**
     * Get entreeId.
     *
     * @return int
     */
    public function getEntreeId()
    {
        return $this->entree->getId();
    }

    /**
     * Set entree.
     *
     * @param Entree $entree
     *
     * @return Service
     */
    public function setEntreeId(Entree $entree)
    {
        $this->entreeId = $entree->getId();

        return $this;
    }
}
