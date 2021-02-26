<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use DateTime;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

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
            #[Route('/blog/{id}/edit', name: 'blog_edit')]
            public function form(Article $article = null, Request $request, ObjectManager $manager) {
                // $article = new Article();

                if (!$article) {
                    $article = new Article();
                }
                // $article->setTitle("Titre d'exemple")
                //         ->setContent("Contenu d'exemple");

                // Créer formulaire
                $form = $this->createFormBuilder($article)
                            // Avec cette version simple les options du form sont gérés dans twig
                            ->add('title')
                            ->add('content')
                            ->add('image')

                            // version 2 des options du formulaire
                            // ->add('title', TextType::class, [
                            //     'attr' => [
                            //         'placeholder' => "Titre de l'article"
                            //     ]
                            // ])
                            // ->add('content', TextareaType::class, [
                            //     'attr' => [
                            //         'placeholder' => "Contenu de l'article"
                            //     ]
                            // ])
                            // ->add('image', TextType::class, [
                            //     'attr' => [
                            //         'placeholder' => "Image de l'article"
                            //     ]
                            // ])
                            // Le bouton est créé dans twig aussi. pas besoin de celui là
                            // ->add('save', SubmitType::class, [
                            //     'label' =>'Enregistrer'
                            // ])
                            ->getForm();
                // Analyser la requête
                $form->handleRequest($request);
                // Control des données
                if ($form->isSubmitted() && $form->isValid()) {
                    if (!$article->getId()) {
                        $article->setCreatedAt(new \DateTime());
                    }

                    $manager->persist($article);
                    $manager->flush();

                    return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
                }

                return $this->render('blog/create.html.twig', [
                    'formArticle' => $form->createView(),//formArticle sera appelé dans Twig
                    'editMode' => $article->getId() !== null
                ]);
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
        