<?php
namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccueilTest extends WebTestCase {

    public function testAccueilPageIsAccessible(): void {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }

    public function testAccueilContientFormations(): void {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table');
    }

    public function testFormationsPageIsAccessible(): void {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $this->assertResponseIsSuccessful();
    }

    public function testFormationsTriTitreAsc(): void {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/title/ASC');
        $this->assertResponseIsSuccessful();
        $firstTitle = $crawler->filter('td h5')->first()->text();
        $this->assertNotEmpty($firstTitle);
    }

    public function testFormationsTriTitreDesc(): void {
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/title/DESC');
        $this->assertResponseIsSuccessful();
        $firstTitle = $crawler->filter('td h5')->first()->text();
        $this->assertNotEmpty($firstTitle);
    }

    public function testFormationsFiltreParTitre(): void {
        $client = static::createClient();
        $crawler = $client->request('POST', '/formations/recherche/title', [], [], [],
            http_build_query(['recherche' => 'Eclipse']));
        $this->assertResponseIsSuccessful();
    }

    public function testPlaylistsPageIsAccessible(): void {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $this->assertResponseIsSuccessful();
    }

    public function testPlaylistsTriNomAsc(): void {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists/tri/name/ASC');
        $this->assertResponseIsSuccessful();
        $firstTitle = $crawler->filter('td')->first()->text();
        $this->assertNotEmpty($firstTitle);
    }

    public function testPlaylistShowOne(): void {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists/playlist/1');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h4');
    }
}