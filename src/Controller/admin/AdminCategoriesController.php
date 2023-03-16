<?php

namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminCategoriesController
 *
 * @author user
 */
class AdminCategoriesController extends AbstractController {

    /**
     * @var type String
     */
    private $pageCategoriesAdmin = "admin/admin.categories.html.twig";

     /**
     * @var type String
     */
    private $redirectToAC = "admin.categories";

    /**
     * @var type CategorieRepository
     */
    private $categorieRepository;


    /**
     * Constructeur
     * @param CategorieRepository $categorieRepository
     */
    public function __construct(CategorieRepository $categorieRepository){
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * @Route("/admin/categories", name="admin.categories")
     * @return Response
     */
    public function index(): Response{
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->pageCategoriesAdmin, [
            'categories' => $categories
        ]);
    }

    /**
     * Méthode de tri des catégories
     * @Route("/admin/categories/tri/{champ}/{ordre}", name="admin.categories.sort")
     * @return Response
     */
    public function sort($champ, $ordre): Response{
        $categories = $this->categorieRepository->findAll($champ, $ordre);
        return $this->render($this->pageCategoriesAdmin, [
            'categories' => $categories
        ]);
    }

    /**
     * Méthode de suppression d'une catégorie
     * @Route("/admin/categories/suppr/{id}", name="admin.categorie.suppr")
     * @param Categorie $categorie
     * @return Response
    */ 
    public function suppr(Categorie $categorie): Response{
        $this->categorieRepository->remove($categorie, true);
        return $this->redirectToRoute($this->redirectToAC);
    }

    /**
     * Méthode d'ajout d'une catégorie
     * @Route("/admin/categories/ajout", name="admin.categorie.ajout")
     * @param Request $request 
     * @return Response
     */
    public function ajout(Request $request): Response{
        $categories = new Categorie();
        $formCategorie = $this->createForm(CategorieType::class, $categories);
        $formCategorie->handleRequest($request);
        if($formCategorie->isSubmitted() && $formCategorie->isValid()){
            $this->categorieRepository->add($categories, true);
            return $this->redirectToRoute('admin.categories');
        }
        return $this->render("admin/admin.categorie.ajout.html.twig", [
            'categories' => $categories,
            'formcategorie' => $formCategorie->createView()
        ]);
    }
}