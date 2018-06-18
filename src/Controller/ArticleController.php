<?php
/**
 * Created by PhpStorm.
 * User: mac
 * Date: 26/05/2018
 * Time: 19:28
 */

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Service\SlackClient;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private $isDebug;

    public function __construct(bool $isDebug)
    {
        $this->isDebug = $isDebug;
    }

    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(ArticleRepository $repository)
    {
        $articles = $repository->findAllOrderedByNewest();

        return $this->render('article/homepage.html.twig',
                [
                    'articles' => $articles,
                ]
            );
    }

    /**
     * @Route("/news/{slug}", name="article_show")
     */
    public function show(Article $article, SlackClient $slack)
    {

        if($article->getSlug() == 'khhaaan'){
            $slack->sendMessage('Kahn', 'Ah, Kirk, my old friend...');
        }

        return $this->render('article/show.html.twig', [
            'article'  => $article,
        ]);
    }

    /**
     * @Route("/news/{slug}/heart", name="article_toggle_heart", methods={"POST"})
     */
    public function toggleArticleHeart(Article $article, LoggerInterface $logger, EntityManagerInterface $em)
    {

        $article->incrementHeartCount();
        $em->flush();

        // TODO - actually heart/unheart the article

        $logger->info('Article is being harted');

        return new JsonResponse(['hearts' => $article->getHeartCount()]);
    }
}