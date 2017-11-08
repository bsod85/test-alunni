<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Alunno;
use AppBundle\Entity\Voto;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class Alunni extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $alunni = [
            ['Angelo', 'Milazzo', [
                [6, null],
                [7, null],
            ]],
            ['Mario', 'Rossi', [
                [3, 'Molto male'],
                [5, 'Un po\' meglio'],
            ]],
            ['Giuseppe', 'Verdi', [
                [10, 'Ottima composizione'],
                [9, null],
            ]],
        ];

        foreach ($alunni as $alunnoArray) {
            $alunno = new Alunno();
            $alunno->setNome($alunnoArray[0]);
            $alunno->setCognome($alunnoArray[1]);
            $alunno->setEmail('angelo@plap.it');

            foreach ($alunnoArray[2] as $votoArray) {
                $voto = new Voto();
                $voto->setValutazione($votoArray[0]);
                $voto->setDescrizione($votoArray[1]);

                $alunno->addVotus($voto);
            }


            $manager->persist($alunno);
        }

        $manager->flush();
    }
}