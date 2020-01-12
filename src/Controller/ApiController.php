<?php

namespace App\Controller;

use App\Repository\TodoRepository;
use App\Service\ApiHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class ApiController extends AbstractController
{

    private $apiHelper;

    /**
     * ApiController constructor.
     * @param ApiHelper $apiHelper
     */
    public function __construct(ApiHelper $apiHelper)
    {
        $this->apiHelper = $apiHelper;
    }

    /**
     * @Route("/", name="api")
     * @param Request $request
     * @param TodoRepository $todoRepository
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request, TodoRepository $todoRepository)
    {
        $findAll = $todoRepository->findAll();

        if ($this->apiHelper->checkHeader($request)) {
            return $this->json(['list' => $findAll]);
        }
        return $this->render('api/index.html.twig', [
            'list' => $findAll,
        ]);
    }

    /**
     * @Route("/create", name="todo_add")
     */
    public function create()
    {

    }
}
