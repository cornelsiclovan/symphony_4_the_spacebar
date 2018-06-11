<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 07/06/2018
 * Time: 15:07
 */

namespace App\Controller;


use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleAdminController extends AbstractController
{
    /**
     * @Route("/admin/article/new")
     */
    public function new(EntityManagerInterface $em){
        die('todo');


        return new Response(sprintf(
            'Hiya! New article id: #%d slug: %s',
            $article->getId(),
            $article->getSlug()
        ));
    }
}