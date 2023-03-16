<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

namespace App\Tests\Repository;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of PlaylistRepositoryTest
 * Contrôler toutes les méthodes ajoutées dans les classes Repository (pour cela, créer une BDD de test)
 *
 * @author uu0✿
 */
class PlaylistRepositoryTest extends KernelTestCase{
     /**
     * Récupère le repository de Playlist
     */
    public function recupRepository(): PlaylistRepository{
        self::bootKernel();
        $repository = self::getContainer()->get(PlaylistRepository::class);
        return $repository;
    }

    /**
     * Récupère le nombre d'enregistrements contenus dans la table Playlist
     */
    public function testNbPlaylists(){
        $repository = $this->recupRepository();
        $nbPlaylists = $repository->count([]);
        $this->assertEquals(27, $nbPlaylists);
    }

    /**
     * Création d'une instance de Playlist avec les champs
     * @return Playlist
     */
    public function newPlaylist(): Playlist{
        $playlist = (new Playlist())
                ->setName("PlaylistTest")
                ->setDescription("Description blablabla");
        return $playlist;
    }
    
    public function testAddPlaylist(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $nbPlaylists = $repository->count([]);
        $repository->add($playlist, true);
        $this->assertEquals($nbPlaylists + 1, $repository->count([]), "erreur lors de l'ajout d'une playlist");
    }

    public function testRemoveFormation(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $nbPlaylists = $repository->count([]);
        $repository->remove($playlist, true);
        $this->assertEquals($nbPlaylists - 1, $repository->count([]), "erreur lors de la suppression d'une playlist");
    }
    
     public function testFindAllOrderByName(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $playlists = $repository->findAllOrderByName("ASC");
        $nbPlaylists = count($playlists);
        $this->assertEquals(29, $nbPlaylists);
        $this->assertEquals("Bases de la programmation (C#)", $playlists[0]->getName());
    }
    
    public function testFindAllOrderByNbFormation(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $playlists = $repository->findAllOrderByNbFormation("ASC");
        $nbPlaylists = count($playlists);
        $this->assertEquals(30, $nbPlaylists);
        $this->assertEquals("PlaylistTest", $playlists[0]->getName());
    }

    public function testFindByContainValue(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $playlists = $repository->findByContainValue("name", "Sujet");
        $nbPlaylists = count($playlists);
        $this->assertEquals(8, $nbPlaylists);
        $this->assertEquals("Exercices objet (sujets EDC BTS SIO)", $playlists[0]->getName());
    }

    public function testFindByContainValueTable(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $playlists = $repository->findByContainValue("name", "MCD", "categories");
        $nbPlaylists = count($playlists);
        $this->assertEquals(5, $nbPlaylists);
        $this->assertEquals("Cours MCD MLD MPD", $playlists[0]->getName());
    }
}
