<?php

namespace App\Controller\admin;

use App\Entity\Formation;
use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\CategorieRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Description of AdminPlaylistController
 *
 * @author user
 */
class AdminPlaylistController extends AbstractController{
    
    /**
     * 
     * @var type String
     */
    private $pagePlaylistsAdmin = "admin/admin.playlists.html.twig";
    
    /**
     * 
     * @var type String
     */
    private $pagePlaylistsAdminAjout = "admin/admin.playlist.ajout.html.twig";
    
    /**
     * 
     * @var type String
     */
    private $pagePlaylistsAdminEdit = "admin/admin.playlist.edit.html.twig";
    
    /**
     *
     * @var type String
     */
    private $redirectToAP = 'admin.playlists';
    
    /**
     *
     * @var type PlaylistRepository
     */
    private $playlistRepository;
    
     /**
     * 
     * @var type CategorieRepository
     */
    private $categorieRepository;    

    
     /**
     * Constructeur 
     * @param PlaylistRepository $playlistRepository
     * @param CategorieRepository $categorieRepository
     */
    function __construct(PlaylistRepository $playlistRepository, 
            CategorieRepository $categorieRepository) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
    }
    
    /**
     * @Route("/admin/playlists", name="admin.playlists")
     * @return Response
     */
    public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->pagePlaylistsAdmin, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }
    
     /**
     * Méthode de tri des playlists
     * @Route("/admin/playlists/tri/{champ}/{ordre}", name="admin.playlists.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    public function sort($champ, $ordre): Response{
        switch($champ){
            case "name":
                    $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                    break;
            case "nbformations":
                    $playlists = $this->playlistRepository->findAllOrderByNbFormation($ordre);
                    break;
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->pagePlaylistsAdmin, [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }
    
     /**
     * Méthode de recherche d'une formation en fonction d'un champs 
     * @Route("/admin/playlists/recherche/{champ}/{table}", name="admin.playlists.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        if($table!="") {
            $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        }else{
            $playlists = $this->playlistRepository->findByContainValueEmpty($champ, $valeur);
        }
        $categories = $this->categorieRepository->findAll();

        return $this->render($this->pagePlaylistsAdmin, [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }  
    
    /**
     * Méthode de tri sur le nombre de formation
     * @Route("/admin/playlists/tri/{ordre}", name="admin.playlists.sortonnbformation")
     * @param type $ordre
     * @return Response
     */
    public function sortOnNbFormation($ordre): Response{
        $playlists = $this->playlistRepository->findAllOrderByNbFormation($ordre);
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->pagePlaylistsAdmin, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * Méthode de suppresion d'une formation
     * @Route("/admin/playlists/suppr/{id}", name="admin.playlist.suppr")
     * @param Formation $playlist
     * @return Reponse
     */ 
    public function suppr(Playlist $playlist): Response{
        $this->playlistRepository->remove($playlist, true);
        return $this->redirectToRoute($this->redirectToAP);
    }
    
    /**
     * Méthode de modification d'une playlist
     * @Route("/admin/playlists/edit/{id}", name="admin.playlist.edit")
     * @param Playlist $playlist
     * @param Request $request
     * @return Reponse
     */
    public function edit(Playlist $playlist, Request $request): Response{
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        $formPlaylist->handleRequest($request);
        if($formPlaylist->isSubmitted() && $formPlaylist->isValid()) {
            $this->playlistRepository->add($playlist, true);
            return $this->redirectToRoute($this->redirectToAP);
        }
        return $this->render($this->pagePlaylistsAdminEdit, [
            'playlist' => $playlist,
            'formplaylist' => $formPlaylist->createView()
        ]);
    }
    
    /**
     * Méthode d'ajout d'une playlist
     * @Route("/admin/playlists/ajout", name="admin.playlist.ajout")
     * @param Request $request
     * @return Reponse
     */
    public function ajout(Request $request): Response {
        $playlist = new Playlist();
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        $formPlaylist->handleRequest($request);
        if($formPlaylist->isSubmitted() && $formPlaylist->isValid()) {
            $this->playlistRepository->add($playlist, true);
            return $this->redirectToRoute($this->redirectToAP);
        }
        return $this->render($this->pagePlaylistsAdminAjout, [
            'playlist' => $playlist,
            'formplaylist' => $formPlaylist->createView()
        ]);
    }
}
