<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    #[Route('/blog', name: 'blog')]
    public function index(ArticleRepository $repo): Response
    {
        // Nous n'avons plus besoin de cette ligne car injection de dependence fait la même chose dans la fonction index en appelant ArticleRepository
        // $repo = $this->getDoctrine()->getRepository(Article::class);
        $articles = $repo->findAll();
        
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles
            ]);
        }
        
        #[Route('/', name: 'home')]
        public function home()
        {
            return $this->render('blog/home.html.twig', [
                'title' => 'Bienvenue les amis !',
                'age' => 33,
                ]);
            }

            // Si on écrit la route blog/new après blog/{id} new sera considéré comme un id et il y aura une erreur. Dans ce cas là d'abord new sera recherché après blog/{id} et pas de confusion
            #[Route('/blog/new', name: 'blog_create')]
            public function create() {
                return $this->render('blog/create.html.twig');
            }
            
            #[Route('/blog/{id}', name: 'blog_show')]
            public function show(Article $article)
            {                
                return $this->render('blog/show.html.twig', [
                    'article' => $article
                    ]);
                }
                
                // v2 de la fonction show
                // public function show($id)
                // {                
                    //     $repo = $this->getDoctrine()->getRepository(Article::class);
                    //     $article = $repo->find($id);
                    
                    //     return $this->render('blog/show.html.twig', [
                        //         'article' => $article
                        //     ]);
                        // }
                        
                        
        }
        