<?php
namespace App\Tests;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FormationRepositoryTest extends KernelTestCase {

    public function getRepository(): FormationRepository {
        self::bootKernel();
        return static::getContainer()->get(FormationRepository::class);
    }

    public function testFindAllOrderByTitleAsc(): void {
        $repo = $this->getRepository();
        $formations = $repo->findAllOrderBy('title', 'ASC');
        $this->assertGreaterThan(0, count($formations));
        $premier = $formations[0]->getTitle();
        $deuxieme = $formations[1]->getTitle();
        $this->assertLessThanOrEqual($deuxieme, $premier);
    }

    public function testFindAllOrderByTitleDesc(): void {
        $repo = $this->getRepository();
        $formations = $repo->findAllOrderBy('title', 'DESC');
        $this->assertGreaterThan(0, count($formations));
        $premier = $formations[0]->getTitle();
        $deuxieme = $formations[1]->getTitle();
        $this->assertGreaterThanOrEqual($deuxieme, $premier);
    }

    public function testFindAllOrderByDateAsc(): void {
        $repo = $this->getRepository();
        $formations = $repo->findAllOrderBy('publishedAt', 'ASC');
        $this->assertGreaterThan(0, count($formations));
        $premier = $formations[0]->getPublishedAt();
        $deuxieme = $formations[1]->getPublishedAt();
        $this->assertLessThanOrEqual($deuxieme, $premier);
    }

    public function testFindByContainValueTitle(): void {
        $repo = $this->getRepository();
        $formations = $repo->findByContainValue('title', 'Eclipse');
        $this->assertGreaterThan(0, count($formations));
        $this->assertStringContainsStringIgnoringCase('Eclipse', $formations[0]->getTitle());
    }

    public function testFindByContainValueTitleVide(): void {
        $repo = $this->getRepository();
        $formations = $repo->findByContainValue('title', '');
        $this->assertGreaterThan(0, count($formations));
    }

    public function testFindAllLasted(): void {
        $repo = $this->getRepository();
        $formations = $repo->findAllLasted(2);
        $this->assertEquals(2, count($formations));
    }

    public function testFindAllForOnePlaylist(): void {
        $repo = $this->getRepository();
        $formations = $repo->findAllForOnePlaylist(1);
        $this->assertGreaterThan(0, count($formations));
        foreach($formations as $formation){
            $this->assertEquals(1, $formation->getPlaylist()->getId());
        }
    }
}