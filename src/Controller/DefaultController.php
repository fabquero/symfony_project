<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Etoile;
use App\Entity\Post;
use App\Form\CategorieType;
use App\Form\EtoileType;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class,$categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $idCategorie = $this->getDoctrine()->getRepository(Categorie::class)->findOneBy(['nom'=>$categorie->getNom()])->getId();
            return $this->redirectToRoute('listeArticles',['id'=>$idCategorie]);
        }

        $article = $this->getDoctrine()->getRepository(Post::class)->getMaxResult();
        return $this->render('default/index.html.twig', [
            'form'=> $form->createView(),
            'article' => $article,
        ]);
    }

    /**
     * @Route("/articles/{post}" ,name="articles", requirements={"post"="\d+"})
     * @return Response
     */
    public function montrerArticle($post)
    {
        $article = $this->getDoctrine()->getRepository(Post::class)->find($post);

        $etoiles = $this->getDoctrine()->getRepository(Etoile::class)->findOneBy(["post"=>$post]);
        return $this->render('default/article.html.twig',[
            'article'=>$article,'etoile'=>$etoiles
        ]);
    }

    /**
     * @Route("user/ajouterArticle/", name="ajouterArticle")
     */
    public function ajouterArticle(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class,$post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $date = new \DateTime('now');
            $post->setDate($date);
            $user = $this->getUser();
            $post->setUtilisateur($user);
            $post->setNbVoteDroite(0);
            $post->setNbVoteGauche(0);
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render('default/ajouterArticle.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/listeArticles/{id}" ,name="listeArticles", requirements={"id"="\d+"})
     */
    public function listeArticle($id)
    {
        $articles = $this->getDoctrine()->getRepository(Post::class)->findBy(['categorie'=>$id]);

        return $this->render('default/listeArticle.html.twig',[
            'articles'=>$articles
        ]);
    }

    /**
     * @Route("/cgu",name="cgu")
     */
    public function cgu()
    {
        return $this->render('default/cgu.html.twig');
    }

    /**
     * @Route("user/articles/{idArticle}/vote/{couleur}",name="argument",requirements={"idArticle"="\d+", "couleur":"[1|0]"})
     */
    public function voteArg($idArticle,$couleur, Request $request)
    {
        $utilisateur = $this->getUser();
        $etoiles = $utilisateur->getEtoiles();
        $vote = false;
        foreach ($etoiles as $etoileUser) {
            if (($etoileUser->getPost()->getId()) == $idArticle){
                $vote = true;
            }
        }
        if ($vote == false) {
            $etoile = new Etoile();
            $form = $this->createForm(EtoileType::class, $etoile);
            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $etoile->setGaucheDroite($couleur);
                $etoile->setPost($this->getDoctrine()->getRepository(Post::class)->find($idArticle));
                $etoile->setUtilisateur($utilisateur);
                $em->persist($etoile);
                $post = new Post();
                $post = $this->getDoctrine()->getRepository(Post::class)->find($idArticle);
                if ($couleur == 1){
                    $post->setNbVoteGauche($post->getNbVoteGauche()+1);

                }else{
                    $post->setNbVoteDroite($post->getNbVoteDroite()+1);
                }
                $em->persist($post);
                $em->flush();
                return $this->redirectToRoute("articles",['post'=>$idArticle]);
            }
            else{

                return $this->render('default/argumenter.html.twig',["form"=>$form->createView()]);
            }
        }
        else{
            return $this->redirectToRoute('home');
        }
    }
}
