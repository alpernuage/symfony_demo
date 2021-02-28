<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        
        //Créer 3 catégories fakées
        for ($i=1; $i <= 3; $i++) { 
            $category = new Category();
            $category->setTitle($faker->sentence())
                    ->setDescription($faker->paragraph());

            $manager->persist($category);

            //Créer entre 4 et 6 articles
            for ($j=1; $j <= mt_rand(4, 6); $j++) { 
                $article = new Article();

                // On ne peut pas utiliser $faker->paragraphs qui nous donne un tableau car setContent demande un string. Nous avons sorti la method faker et stocké la valeur dans une variable                
                $content = '<p>' . join($faker->paragraphs(5), '</p><p>') . '</p>';

                $article->setTitle($faker->sentence())
                        ->setContent($content)
                        ->setImage($faker->imageUrl())
                        ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                        ->setCategory($category);
    
                $manager->persist($article);

                //On donne des commentaires à l'article
                for ($k=0; $k <= mt_rand(4, 10); $k++) { 
                    $comment = new Comment;

                    $content = '<p>' . join($faker->paragraphs(2), '</p><p>') . '</p>';

                    // La date de commentaire doit être après la date de création
                    // $now = new \DateTime();
                    // $interval = $now->diff($article->getCreatedAt());
                    // $days = $interval->days;
                    // $minimum = '-' . $days . ' days'; // par exemple -100 days

                    // Code raccourci qui est égal au code en haut
                    $days = (new \DateTime())->diff($article->getCreatedAt())->days;

                    $comment->setAuthor($faker->name)
                            ->setContent($content)
                            ->setCreatedAt($faker->dateTimeBetween('-' . $days . ' days'))
                            ->setArticle($article);

                    $manager->persist(($category));
                }
            }
        }

        $manager->flush();
    }
}
