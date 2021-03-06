<?php

namespace TDB\PasswordManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Acces
 *
 * @ORM\Table(name="acces")
 * @ORM\Entity(repositoryClass="TDB\PasswordManagerBundle\Repository\AccesRepository")
 */
class Acces
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dateCreation = new \Datetime();
        $this->dateModification = new \Datetime();
    }
    /**
     * @ORM\ManyToOne(targetEntity="TDB\PasswordManagerBundle\Entity\Service", inversedBy="access")
     * @ORM\JoinColumn(nullable=false)
     */
    private $service;

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
     * @ORM\Column(name="clef", type="string", length=255)
     */
    private $clef;

    /**
     * @var string
     *
     * @ORM\Column(name="valeur", type="string", length=255, nullable=true)
     */
    private $valeur;

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
     * @ORM\Column(name="service_id", type="integer")
     */
    private $serviceId;

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
     * Set clef.
     *
     * @param string $clef
     *
     * @return Acces
     */
    public function setClef($clef)
    {
        $this->clef = $clef;

        return $this;
    }

    /**
     * Get clef.
     *
     * @return string
     */
    public function getClef()
    {
        return $this->clef;
    }

    /**
     * Set valeur.
     *
     * @param string $valeur
     *
     * @return Acces
     */
    public function setValeur($valeur)
    {
        $this->valeur = $valeur;

        return $this;
    }

    /**
     * Get valeur.
     *
     * @return string
     */
    public function getValeur()
    {
        return $this->valeur;
    }

    /**
     * Set dateCreation.
     *
     * @param \DateTime $dateCreation
     *
     * @return Acces
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
     * @return Acces
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
     * Set service.
     *
     * @param Service $service
     *
     * @return Acces
     */
    public function setService(Service $service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service.
     *
     * @return Service
     */
    public function getService()
    {
        return $this->service;
    }
    /**
     * Get serviceId.
     *
     * @return int
     */
    public function getServiceId()
    {
        return $this->service->getId();
    }

    /**
     * Set serviceId.
     *
     * @param Service $service
     *
     * @return Service
     */
    public function setServiceId(Service $service)
    {
        $this->serviceId = $service->getId();

        return $this;
    }
}
