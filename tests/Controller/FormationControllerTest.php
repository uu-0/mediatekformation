<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Test Fonctionnel
 * Description of FormationControllerTest
 *
 * @author uu0✿
 */
class FormationControllerTest extends WebTestCase{
    
    private const FORMATIONSPATH = '/formations';
    
    /**
     * Test qui contrôle l'accès de la page des formations
    */
    public function testAccesPage() {
        $client = static::createClient();
        $client->request('GET', self::FORMATIONSPATH);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

    }
    
    /**
     * Test qui vérifie le tri croissant des playlists
     */
    public function testPlaylistsTriAsc(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/name/ASC/playlist');
        $this->assertSelectorTextContains('th', 'formation');
        $this->assertCount(5, $crawler->filter('th'));
        $this->assertSelectorTextContains('h5', 'Bases de la programmation n°74 - POO : collections');
    }
    
    /**
     * Test qui vérifie le tri croissant des formations
     */
    public function testFormationsTriAsc(){
        $client = static::createClient();
        $crawler = $client->request('GET', '/formations/tri/title/ASC');
        $this->assertSelectorTextContains('th', 'formation');
        $this->assertCount(5, $crawler->filter('th'));
        $this->assertSelectorTextContains('h5', 'Android Studio (complément n°1) : Navigation Drawer et Fragment');
    }
    
    /**
     * Test qui vérifie le tri par date
     */
     public function testTriDate()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', 'formations/tri/publishedAt/ASC');
        $this->assertSelectorTextContains('th', 'formation');
        $this->assertCount(5, $crawler->filter('th'));
        $this->assertSelectorTextContains('h5', 'Cours UML (1 à 7 / 33) : introduction');
    }
    
    /**
     * Test qui contrôle le tri par filtre des formations
     */
     public function testFiltreFormations()
    {
        $client = static::createClient();
        $client->request('GET', '/formations'); 
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'UML'
        ]);
        //vérifie le nombre de lignes obtenues
        $this->assertCount(10, $crawler->filter('h5'));
        // vérifie si la formation correspond à la recherche
         $this->assertSelectorTextContains('h5', 'UML');
    }
    
    /**
     * Test qui contrôle le tri par filtre des playlists
     */
     public function testFiltrePlaylists()
    {
        $client = static::createClient();
        $client->request('GET', '/formations/recherche/name/playlist'); 
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Eclipse'
        ]);
        //vérifie le nombre de lignes obtenues
        $this->assertCount(9, $crawler->filter('h5'));
        // vérifie si la formation correspond à la recherche
         $this->assertSelectorTextContains('h5', 'Eclipse');
    }
    
    /**
     * Test qui contrôle le tri par filtre des catégories
     */
    public function testFiltreCategories()
    {
        $client = static::createClient();
        $client->request('GET', '/formations/recherche/id/categories'); 
        $crawler = $client->submitForm('filtrer', [
            'recherche' => 'Java'
        ]);
        //vérifie le nombre de lignes obtenues
        $this->assertCount(8, $crawler->filter('h5'));
        // vérifie si la formation correspond à la recherche
         $this->assertSelectorTextContains('h5', 'Java REACT');
    }
    
    /**
     * Test qui contrôle l'accès au détail d'une formation
     */
    public function testLinkFormations() {
        $client = static::createClient();
        $client->request('GET','/formations');
        $client->clickLink("image réduite de formation");
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $this->assertEquals('/formations/formation/1', $uri);
    }
}
