<?php

namespace TDB\PasswordManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entree
 *
 * @ORM\Table(name="entree")
 * @ORM\Entity(repositoryClass="TDB\PasswordManagerBundle\Repository\EntreeRepository")
 */
class Entree
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dateCreation = new \Datetime();
        $this->dateModification = new \Datetime();
        $this->services = [];
    }

    /**
     * @ORM\OneToMany(targetEntity="TDB\PasswordManagerBundle\Entity\Service", mappedBy="entree")
     */
    private $services;

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
     * @ORM\Column(name="entree_nom", type="string", length=255)
     */
    private $entreeNom;

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
     * @var string
     *
     * @ORM\Column(name="notes", type="text", nullable=true)
     */
    private $notes;

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
     * Set entreeNom.
     *
     * @param string $entreeNom
     *
     * @return Entree
     */
    public function setEntreeNom($entreeNom)
    {
        $this->entreeNom = $entreeNom;

        return $this;
    }

    /**
     * Get entreeNom.
     *
     * @return string
     */
    public function getEntreeNom()
    {
        return $this->entreeNom;
    }

    /**
     * Set dateCreation.
     *
     * @param \DateTime $dateCreation
     *
     * @return Entree
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
     * @return Entree
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
     * Set notes.
     *
     * @param string $notes
     *
     * @return Entree
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes.
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Add service.
     *
     * @param \TDB\PasswordManagerBundle\Entity\Service $service
     *
     * @return Entree
     */
    public function addService(\TDB\PasswordManagerBundle\Entity\Service $service)
    {
        $this->services[] = $service;
        $service->setEntree($this);
        return $this;
    }
    /**
     * Set services.
     *
     * @param array
     *
     * @return Entree
     */
    public function setServices($tab)
    {
        $this->services = $tab;
        return $this;
    }

    /**
     * Remove service.
     *
     * @param \TDB\PasswordManagerBundle\Entity\Service $service
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeService(\TDB\PasswordManagerBundle\Entity\Service $service)
    {
        return $this->services->removeElement($service);
    }

    /**
     * Get services.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServices()
    {
        return $this->services;
    }
}
