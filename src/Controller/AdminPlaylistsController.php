<?php
namespace App\Controller;

use App\Entity\Playlist;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPlaylistsController extends AbstractController {

    private $playlistRepository;
    private $formationRepository;
    private $categorieRepository;

    function __construct(PlaylistRepository $playlistRepository,
            FormationRepository $formationRepository,
            CategorieRepository $categorieRepository) {
        $this->playlistRepository = $playlistRepository;
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

    #[Route('/admin/playlists', name: 'admin.playlists')]
    public function index(): Response{
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render('admin/playlists.html.twig', [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

   #[Route('/admin/playlists/tri/{champ}/{ordre}', name: 'admin.playlists.sort')]
    public function sort($champ, $ordre): Response{
        if($champ == "nbformations"){
            $playlists = $this->playlistRepository->findAllOrderByNbFormations($ordre);
        }else{
            $playlists = $this->playlistRepository->findAllOrderByName($ordre);
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render('admin/playlists.html.twig', [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    #[Route('/admin/playlists/recherche/{champ}/{table}', name: 'admin.playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table=""): Response{
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render('admin/playlists.html.twig', [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    #[Route('/admin/playlists/suppr/{id}', name: 'admin.playlists.suppr')]
    public function suppr($id): Response{
        $playlist = $this->playlistRepository->find($id);
        if($playlist->getFormations()->isEmpty()){
            $this->playlistRepository->remove($playlist);
        }
        return $this->redirectToRoute('admin.playlists');
    }

    #[Route('/admin/playlists/ajout', name: 'admin.playlists.ajout')]
    public function ajout(Request $request): Response{
        $playlist = new Playlist();
        return $this->showForm($request, $playlist, false);
    }

    #[Route('/admin/playlists/edit/{id}', name: 'admin.playlists.edit')]
    public function edit(Request $request, $id): Response{
        $playlist = $this->playlistRepository->find($id);
        return $this->showForm($request, $playlist, true);
    }

    private function showForm(Request $request, Playlist $playlist, bool $edit): Response{
        if($request->getMethod() == "POST"){
            $playlist->setName($request->get("name"));
            $playlist->setDescription($request->get("description"));
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin.playlists');
        }
        $formations = $edit ? $this->formationRepository->findAllForOnePlaylist($playlist->getId()) : [];
        return $this->render('admin/playlistform.html.twig', [
            'playlist' => $playlist,
            'formations' => $formations,
            'edit' => $edit
        ]);
    }
}