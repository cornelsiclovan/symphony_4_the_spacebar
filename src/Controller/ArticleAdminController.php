<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 07/06/2018
 * Time: 15:07
 */

namespace App\Controller;


use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleAdminController extends AbstractController
{
    /**
     * @Route("/admin/article")
     */
    public function show(ArticleRepository $articleRepository, Request $request, PaginatorInterface $paginator){

        $q = $request->query->get('q');
        $queryBuilder = $articleRepository->getWithSearchQueryBuilder($q);

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('article_admin/homepage.html.twig',
            [
            'pagination' => $pagination
            ]
        );
    }
}