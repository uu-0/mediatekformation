<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Repository;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of CategorieRepositoryTest
 * Contrôler toutes les méthodes ajoutées dans les classes Repository (pour cela, créer une BDD de test)
 *
 * @author uu0✿
 */
class CategorieRepositoryTest extends KernelTestCase{
    
    /**
     * Récupère le repository de Catégorie
     */
    public function recupRepository(): CategorieRepository{
        self::bootKernel();
        $repository = self::getContainer()->get(CategorieRepository::class);
        return $repository;
    }

    /**
     * Récupère le nombre d'enregistrements contenus dans la table Catégorie
     */
    public function testNbCategories(){
        $repository = $this->recupRepository();
        $nbCategories = $repository->count([]);
        $this->assertEquals(9, $nbCategories);
    }

    /**
     * Création d'une instance de Catégorie avec les champs
     * @return Categorie
     */
    public function newCategorie(): Categorie{
        $categorie = (new Categorie())
                ->setName("CatégorieTest");
        return $categorie;
    }

     public function testAddCategorie(){
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $nbCategories = $repository->count([]);
        $repository->add($categorie, true);
        $this->assertEquals($nbCategories + 1, $repository->count([]), "erreur lors de l'ajout d'une catégorie");
    }

    public function testRemoveCategorie(){
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $repository->add($categorie, true);
        $nbCategories = $repository->count([]);
        $repository->remove($categorie, true);
        $this->assertEquals($nbCategories - 1, $repository->count([]), "erreur lors de la suppression d'une catégorie");
    }

     public function testFindAllForOnePlaylist(){
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $repository->add($categorie, true);
        $categories = $repository->findAllForOnePlaylist(3);
        $nbCategories = count($categories);
        $this->assertEquals(2, $nbCategories);
        $this->assertEquals("POO",$categories[0]->getName());
    }
}
