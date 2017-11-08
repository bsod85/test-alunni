<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AlunnoControllerTest extends WebTestCase
{
    public function testLista()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/alunni/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('#tabella-alunni tbody tr')->count() > 1);
    }

    private function getUrlModificaVoti() {

        $client = static::createClient();

        $crawler = $client->request('GET', '/alunni/');

        return $crawler->filter('#tabella-alunni tbody tr a:contains("Modifica")')->eq(0)->link()->getUri();
    }

    public function testModificaVoto()
    {
        $client = static::createClient();

        $url = $this->getUrlModificaVoti();

        $crawler = $client->request('GET', $url);

        $form = $crawler->selectButton('appbundle_alunno[submit]')->form();

        $values = $form->getPhpValues();

        $this->assertTrue(isset($values['appbundle_alunno']['voti'][0]['valutazione']));

        $this->assertFalse($values['appbundle_alunno']['voti'][0]['valutazione'] == 5);

        $values['appbundle_alunno']['voti'][0]['valutazione'] = 5;

        $client->followRedirects();

        $crawler = $client->request($form->getMethod(), $form->getUri(), $values,
            $form->getPhpFiles());

        $form = $crawler->selectButton('appbundle_alunno[submit]')->form();

        $values = $form->getPhpValues();

        $this->assertTrue(isset($values['appbundle_alunno']['voti'][0]['valutazione']));

        $this->assertTrue($values['appbundle_alunno']['voti'][0]['valutazione'] == 5);
    }

    public function testAggiungiVoto()
    {
        $client = static::createClient();

        $url = $this->getUrlModificaVoti();

        $crawler = $client->request('GET', $url);

        $form = $crawler->selectButton('appbundle_alunno[submit]')->form();

        $values = $form->getPhpValues();

        $numeroVoti = count($values['appbundle_alunno']['voti']);

        $values['appbundle_alunno']['voti'][$numeroVoti]['valutazione'] = 5;

        $client->followRedirects();

        $crawler = $client->request($form->getMethod(), $form->getUri(), $values,
            $form->getPhpFiles());

        $form = $crawler->selectButton('appbundle_alunno[submit]')->form();

        $values = $form->getPhpValues();

        $this->assertEquals(count($values['appbundle_alunno']['voti']), $numeroVoti+1);

        $this->assertEquals($values['appbundle_alunno']['voti'][$numeroVoti]['valutazione'], 5);
    }
}
