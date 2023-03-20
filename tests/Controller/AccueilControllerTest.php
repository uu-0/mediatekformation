<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Test Fonctionnel
 * Description of AccueilControllerTest
 *
 * @author uu0✿
 */
class AccueilControllerTest extends WebTestCase {
    /**
     * Test accès à la page d'accueil
     */
     public function testAccesPage(){
       $client = static::createClient();
       $client->request('GET', '/');
       $this->assertResponseStatusCodeSame(Response::HTTP_OK);
   }
}
