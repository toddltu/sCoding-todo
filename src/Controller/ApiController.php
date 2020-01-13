<?php
// http://www.inanzzz.com/index.php/post/nx2b/validating-serialising-and-mapping-json-request-to-model-classes

namespace App\Controller;

use App\Entity\Todo;
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
     * @Route("/", name="api", methods={"GET","POST"})
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
     * @Route("/create", name="todo_add", methods={"GET","POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        if ($this->apiHelper->checkHeader($request)) {
            /** @var Todo $object */
            $object = $this->apiHelper->validate($request->getContent(), Todo::class);

            $em = $this->getDoctrine()->getManager();
            $em->persist($object);
            $em->flush();

            return $this->json(['id'=> $object->getId(), 'item'=>$object, 'status'=>201],201);
        }



    }
}
