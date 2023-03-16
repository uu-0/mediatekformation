<?php

namespace App\Tests;

use App\Entity\Formation;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Description of DateTest
 *
 * @author uu0✿
 */
class DateTest extends TestCase{
    
    /**
     * Tests unitaires sur la méthode getPublishedAtString() de la classe Formation 
     * pour voir si elle retourne la bonne date au bon format
     */
   public function testGetPublishedAtString(){
       $formation = new Formation();
       $formation->setPublishedAt(new DateTime("2023-03-16"));
       $this->assertEquals("16/03/2023", $formation->getPublishedAtString());
    }
    
}
