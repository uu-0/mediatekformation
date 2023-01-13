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
     * Constructeur de classe
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
     * Méthode de suppression d'une formation
     * @Route ("/admin/suppr/{id}", name="admin.formation.suppr")
     * @param Formation $formation
     * @return Response
     */
   public function suppr(Formation $formation): Response{
       $this->formationrepository->remove($formation, true);
       return $this->redirectToRoute('admin.formations');
   }
   
   /**
    * Méthode de modification d'une formation
    * Création du formulaire, récupèration de la requête (handleRequest), test validité formulaire
    * @Route ("/admin/edit/{id}", name="admin.formation.edit")
    * @param Formation $formation
    * @return Response
    */
   public function edit(Formation $formation, Request $request):Response{
       $formFormation = $this->createForm(FormationType::class, $formation);

       $formFormation->handleRequest($request);
       if($formFormation->isSubmitted() && $formFormation->isValid()){
           $this->repository->add($formation, true);
           return $this->redirectToRoute('admin.formations');
       }
       return $this->render("admin/admin.formation.edit.html.twig", [
           'formation' => $formation,
           'formformation' => $formFormation->createView()
       ]);
   }
   
   /**
    * Méthode d'ajout d'une formation
    * @Route ("/admin/ajout", name="admin.formation.ajout")
    * @param Request $request
    * @return Response
    */
   public function ajout(Request $request): Response{
       $formation = new Formation();
       $formFormation = $this->createForm(FormationType::class, $formation);
       
        $formFormation->handleRequest($request);
        if($formFormation->isSubmitted()&& $formFormation->isValid()){
            $this->formationRepository->add($formation, true);
            return $this->redirectToRoute('admin.formations');
        }
        return $this->render("admin/admin.formation.ajout.html.twig",[
            'formation' => $formation,
            'formformation' => $formFormation->createView()
        ]);
   }
   
   /**
     * Tri les formations 
     * @Route("/formations/tri/{champ}/{ordre}/{table}", name="formations.sort")
     * @param type $champ
     * @param type $ordre
     * @param type $table
     * @return Response
     */
    public function sort($champ, $ordre, $table=""): Response{
        if($table!=""){
           $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        }else{
            $formations = $this->formationRepository->findByOrderBy($champ, $ordre, $table="");
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->pagesFormations, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }   
    
     /**
     * @Route("/admin/formations/recherche/{champ}/{table}", name="admin.formations.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table = ""): Response {
        if ($this->isCsrfTokenValid('filtre_' . $champ, $request->get('_token'))) {
            $valeur = $request->get("recherche");
            if ($table != "") {
                $formations = $this->formationRepository->findByContainValueTable($champ, $valeur, $table);
            } else {
                $formations = $this->formationRepository->findByContainValue($champ, $valeur);
            }
            $categories = $this->categorieRepository->findAll();
            return $this->render($this->pagesFormationsAdmin, [
                        'formations' => $formations,
                        'categories' => $categories,
                        'valeur' => $valeur,
                        'table' => $table
            ]);
        }
        return $this->redirectToRoute($this->redirectToAF);
    }
    
    /**
     * @Route("/admin/formations/rechercher/{champ}/{table}", name="admin.formations.findallcontaincategories")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContainCategories($champ, Request $request, $table): Response {
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValueTable($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render($this->pagesFormationsAdmin, [
                    'formations' => $formations,
                    'categories' => $categories,
                    'valeur' => $valeur,
                    'table' => $table
        ]);
    }

}