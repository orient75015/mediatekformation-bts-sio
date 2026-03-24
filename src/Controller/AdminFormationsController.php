<?php
namespace App\Controller;

use App\Entity\Formation;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminFormationsController extends AbstractController {

    private $formationRepository;
    private $playlistRepository;
    private $categorieRepository;

    function __construct(FormationRepository $formationRepository,
            PlaylistRepository $playlistRepository,
            CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
    }

    #[Route('/admin/formations', name: 'admin.formations')]
    public function index(): Response{
        $formations = $this->formationRepository->findAllOrderBy('publishedAt', 'DESC');
        $categories = $this->categorieRepository->findAll();
        return $this->render('admin/formations.html.twig', [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    #[Route('/admin/formations/tri/{champ}/{ordre}', name: 'admin.formations.sort')]
public function sort($champ, $ordre): Response{
    if($champ == "name"){
        $formations = $this->formationRepository->findAllOrderBy('name', $ordre, 'playlist');
    }else{
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre);
    }
    $categories = $this->categorieRepository->findAll();
    return $this->render('admin/formations.html.twig', [
        'formations' => $formations,
        'categories' => $categories
    ]);
}

    #[Route('/admin/formations/recherche/{champ}/{table}', name: 'admin.formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render('admin/formations.html.twig', [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    #[Route('/admin/formations/suppr/{id}', name: 'admin.formations.suppr')]
    public function suppr($id): Response{
        $this->formationRepository->remove($this->formationRepository->find($id));
        return $this->redirectToRoute('admin.formations');
    }

    #[Route('/admin/formations/ajout', name: 'admin.formations.ajout')]
    public function ajout(Request $request): Response{
        $formation = new Formation();
        return $this->showForm($request, $formation, false);
    }

    #[Route('/admin/formations/edit/{id}', name: 'admin.formations.edit')]
    public function edit(Request $request, $id): Response{
        $formation = $this->formationRepository->find($id);
        return $this->showForm($request, $formation, true);
    }

    private function showForm(Request $request, Formation $formation, bool $edit): Response{
        $playlists = $this->playlistRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        if($request->getMethod() == "POST"){
            $formation->setTitle($request->get("title"));
            $formation->setDescription($request->get("description"));
            $formation->setVideoId($request->get("videoId"));
            $formation->setPublishedAt(new \DateTime($request->get("publishedAt")));
            $playlist = $this->playlistRepository->find($request->get("playlist"));
            $formation->setPlaylist($playlist);
            $formation->getCategories()->clear();
            $categoriesIds = $request->get("categories") ?? [];
            foreach($categoriesIds as $categorieId){
                $categorie = $this->categorieRepository->find($categorieId);
                $formation->addCategory($categorie);
            }
            $this->formationRepository->add($formation);
            return $this->redirectToRoute('admin.formations');
        }
        return $this->render('admin/formationform.html.twig', [
            'formation' => $formation,
            'playlists' => $playlists,
            'categories' => $categories,
            'edit' => $edit
        ]);
    }
}