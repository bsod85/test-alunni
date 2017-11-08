<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Voto
 *
 * @ORM\Table(name="voto")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VotoRepository")
 */
class Voto
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
     * @var Alunno
     *
     * @ORM\ManyToOne(targetEntity="Alunno", inversedBy="voti")
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $alunno;

    /**
     * @var string
     *
     * @ORM\Column(name="valutazione", type="decimal", precision=3, scale=1)
     */
    private $valutazione;

    /**
     * @var string
     *
     * @ORM\Column(name="descrizione", type="text", nullable=true)
     */
    private $descrizione;


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
     * Set valutazione
     *
     * @param float $valutazione
     *
     * @return Voto
     */
    public function setValutazione($valutazione)
    {
        // non vogliamo che un valore equivalente, ma non identico triggeri l'invio email di notifica
        if($valutazione == $this->valutazione) return;

        $this->valutazione = sprintf('%.1f', $valutazione);

        return $this;
    }

    /**
     * Get valutazione
     *
     * @return float
     */
    public function getValutazione()
    {
        return $this->valutazione;
    }

    /**
     * Set descrizione
     *
     * @param string $descrizione
     *
     * @return Voto
     */
    public function setDescrizione($descrizione)
    {
        $this->descrizione = $descrizione;

        return $this;
    }

    /**
     * Get descrizione
     *
     * @return string
     */
    public function getDescrizione()
    {
        return $this->descrizione;
    }

    /**
     * @return Alunno
     */
    public function getAlunno()
    {
        return $this->alunno;
    }

    /**
     * @param Alunno $alunno
     */
    public function setAlunno(Alunno $alunno = null)
    {
        $this->alunno = $alunno;
    }


}

