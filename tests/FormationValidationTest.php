<?php
namespace App\Tests;

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FormationValidationTest extends KernelTestCase {

    public function testDateNotFuture(): void {
        self::bootKernel();
        $validator = static::getContainer()->get(ValidatorInterface::class);
        
        $formation = new Formation();
        $formation->setTitle("Test");
        $formation->setPublishedAt(new \DateTime("2030-01-01"));
        
        $violations = $validator->validate($formation);
        $this->assertGreaterThan(0, count($violations));
    }
    
    public function testDateValid(): void {
        self::bootKernel();
        $validator = static::getContainer()->get(ValidatorInterface::class);
        
        $formation = new Formation();
        $formation->setTitle("Test");
        $formation->setPublishedAt(new \DateTime("2020-01-01"));
        
        $violations = $validator->validate($formation);
        $this->assertEquals(0, count($violations));
    }
}