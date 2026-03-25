<?php
namespace App\Controller;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoriesController extends AbstractController {

    private $categorieRepository;

    function __construct(CategorieRepository $categorieRepository) {
        $this->categorieRepository = $categorieRepository;
    }

    #[Route('/admin/categories', name: 'admin.categories')]
    public function index(Request $request): Response{
        $categories = $this->categorieRepository->findAll();
        $erreur = "";
        if($request->getMethod() == "POST"){
            $name = $request->get("name");
            $exist = $this->categorieRepository->findOneBy(['name' => $name]);
            if($exist){
                $erreur = "La catégorie « ".$name." » existe déjà.";
            }else{
                $categorie = new Categorie();
                $categorie->setName($name);
                $this->categorieRepository->add($categorie);
                return $this->redirectToRoute('admin.categories');
            }
        }
        return $this->render('admin/categories.html.twig', [
            'categories' => $categories,
            'erreur' => $erreur
        ]);
    }

    #[Route('/admin/categories/suppr/{id}', name: 'admin.categories.suppr')]
    public function suppr($id): Response{
        $categorie = $this->categorieRepository->find($id);
        if($categorie->getFormations()->isEmpty()){
            $this->categorieRepository->remove($categorie);
        }
        return $this->redirectToRoute('admin.categories');
    }
}