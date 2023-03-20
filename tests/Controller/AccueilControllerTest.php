<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of AccueilControllerTest
 *
 * @author uu0✿
 */
class AccueilControllerTest extends WebTestCase {
    public function testAccesPage(){
       $client = static::createClient();
       $client->request('GET', '/');
       $this->assertResponseIsSuccessful();
    }
}
