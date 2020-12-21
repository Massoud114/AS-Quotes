<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Quote;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
    	$faker = Factory::create('fr_FR');
		// Création des tags
		$tags = [];
		for ($i = 0; $i < 10; $i++) {
			$tag = new Tag();
			$tag->setName($faker->domainName);
			$manager->persist($tag);
			$tags[] = $tag;
		}

		// Création des quotes
		for ($i = 0; $i <= 50; $i++) {
			$quote = new Quote();
			$quote->setAllowed(true)
				->setPublishedAt(new \DateTime('now'))
				->setDescription($faker->sentence(14));
			$nbComment = mt_rand(5, 10);
			for ($j = 0; $j < $nbComment; $j++) {
				$comment = new Comment();
				$comment->setContent($faker->sentence(15));
				$manager->persist($comment);
				$quote->addComment($comment);
			}
			$quoteCollection = new ArrayCollection();
			for ($j = 0; $j < 9; $j++) {
				if ((mt_rand(1, 9) + $j) % 2){
					$quoteCollection->add($tags[$j]);
				}
			}

			$quote->addTag($quoteCollection);
			$manager->persist($quote);
		}
        $manager->flush();
    }
}
