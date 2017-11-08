<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Alunno
 *
 * @ORM\Table(name="alunno")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AlunnoRepository")
 */
class Alunno
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Voto
     *
     * @ORM\OneToMany(targetEntity="Voto", mappedBy="alunno", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $voti;

    /**
     * @var string
     *
     * @ORM\Column(name="nome", type="string", length=255)
     */
    private $nome;

    /**
     * @var string
     *
     * @ORM\Column(name="cognome", type="string", length=255)
     */
    private $cognome;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * Alunno constructor.
     * @param Voto $voti
     */
    public function __construct()
    {
        $this->voti = new ArrayCollection();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nome
     *
     * @param string $nome
     *
     * @return Alunno
     */
    public function setNome($nome)
    {
        $this->nome = $nome;

        return $this;
    }

    /**
     * Get nome
     *
     * @return string
     */
    public function getNome()
    {
        return $this->nome;
    }

    /**
     * Set cognome
     *
     * @param string $cognome
     *
     * @return Alunno
     */
    public function setCognome($cognome)
    {
        $this->cognome = $cognome;

        return $this;
    }

    /**
     * Get cognome
     *
     * @return string
     */
    public function getCognome()
    {
        return $this->cognome;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Alunno
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return Voto
     */
    public function getVoti()
    {
        return $this->voti;
    }

    public function addVotus(Voto $voto) {

        $voto->setAlunno($this);
        $this->voti->add($voto);
    }

    public function removeVotus(Voto $voto) {
        $voto->setAlunno(null);
        $this->voti->removeElement($voto);
    }
}

