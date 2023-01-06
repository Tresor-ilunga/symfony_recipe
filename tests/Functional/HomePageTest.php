<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomePageTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $button = $crawler->selectLink('Inscription');
        $this->assertEquals(1, count($button));

        $recipes = $crawler->filter('.recipes .card');
        $this->assertEquals(3, count($recipes));

        $this->assertSelectorTextContains('h1', 'Bienvenu sur Recipe');
    }
}
