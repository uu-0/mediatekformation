<?php

namespace App\Tests\Validations;

use App\Entity\Formation;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Contrôle si la date de publication n'est pas postérieure à ajd lors de l'ajout/édition d'une formation
 *
 * @author samsam
 */
class DateValidationsTest extends KernelTestCase{

    /*
     * Crée et retourne un objet de type Formation
     */
    public function getFormation(): Formation{
        return (new Formation())
        ->setTitle('Nouvelle formation')
        ->setPublishedAt(new DateTime("2033/03/16"));
    }

    /*
     * On attend une erreur pour la date "2033/03/16" car elle est postérieure à aujourd'hui
     */
    public function testValidationDateFormation(){
        $formation = $this->getFormation()->setPublishedAt(new DateTime("2033/03/16"));
        $this->assertErrors($formation, 1); // 1 car "2033/03/16" provequera une erreur (0 si pas d'erreur)
    }
    
    /**
     * Fonction d'assertion qui gère l'appel au kernel et à 'assertCount'
     * @param Formation $formation (objet à test)
     * @param int $nbErreursAttendues (nombre d'erreurs attendues)
     * @param string $message
     */
    public function assertErrors(Formation $formation, int $nbErreursAttendues){
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($formation);
        $this->assertCount($nbErreursAttendues,$error);
    }
}