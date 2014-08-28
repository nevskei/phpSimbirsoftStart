<?php

namespace Acme\DemoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Acme\DemoBundle\Entity\Book;

class LoadBooks implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1 ; $i < 100 ; $i++) {
            $book = new Book();
            $book->setTitle("Book {$i}");
            $book->setPages(rand(100, 1000));

            $manager->persist($book);
        }

        $manager->flush();
    }
}