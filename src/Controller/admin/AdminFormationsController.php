<?php

namespace App\Controller\admin;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminFormationsController
 *
 * @author user
 */
class AdminFormationsController extends AbstractController {

    /**
     * 
     * @var type String
     */
    private $pagesFormationsAdmin = "admin/admin.formations.html.twig";
    
    /**
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * Constructeur
     * @param FormationRepository $formationRepository
     * @param CategorieRepository $categorieRepository
     */
    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * @Route("/admin", name="admin.formations")
     * @return Response
     */
    public function index(): Response {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->pagesFormationsAdmin, [
                    'formations' => $formations,
                    'categories' => $categories
        ]);
    }
    
    /**
     * Suppression d'une formation
     * @Route ("/admin/suppr/{id}", name="admin.formation.suppr")
     * @param Formation $formation
     * @return Response
     */
   public function suppr(Formation $formation): Response{
       $this->formationrepository->remove($formation, true);
       return $this->redirectToRoute('admin.formations');
   }
   
   /**
    * Modification d'une formation
    * @Route ("/admin/edit/{id}", name="admin.formation.edit")
    * @param Formation $formation
    * @return Response
    */
   public function edit(Formation $formation):Response{
       return $this->render("admin/admin.formation.edit.html.twig", [
           'formation' => $formation
       ]);
   }
}