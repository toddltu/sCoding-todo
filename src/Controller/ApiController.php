<?php
// http://www.inanzzz.com/index.php/post/nx2b/validating-serialising-and-mapping-json-request-to-model-classes

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoType;
use App\Repository\TodoRepository;
use App\Service\ApiHelper;
use Doctrine\ORM\EntityManagerInterface;
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
     * @Route("/", name="todo_index", methods={"GET","POST"})
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
     * @Route("/create", name="todo_create", methods={"GET","POST"})
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

        $todo = new Todo();
        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($todo);
            $entityManager->flush();

            return $this->redirectToRoute('todo_index');
        }

        return $this->render('api/new.html.twig', [
            'todo' => $todo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/update/{id}", name="todo_update", methods={"GET", "POST", "PUT"})
     * @param Request $request
     * @param Todo $todo
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, Todo $todo, EntityManagerInterface $entityManager)
    {
        if ($this->apiHelper->checkHeader($request, 1)) {
            /** @var Todo $object */
            $object = $this->apiHelper->validate($request->getContent(), Todo::class);
            $todo->setTitle($object->getTitle());
            $todo->setContent($object->getContent());
            $todo->setInStatus($object->getInStatus());
            $entityManager->flush();


            return $this->json(['result'=> true, 'status'=>201],201);
        }

        $form = $this->createForm(TodoType::class, $todo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('todo_index');
        }

        return $this->render('api/update.html.twig', [
            'todo' => $todo,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/view/{id}", name="todo_view", methods={"GET","POST"})
     * @param Request $request
     * @param Todo $todo
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function view(Request $request, Todo $todo)
    {
        if ($this->apiHelper->checkHeader($request)) {
            return $this->json(['id'=> $todo->getId(), 'item'=>$todo, 'status'=>200]);
        }

        return $this->render('api/view.html.twig', [
            'todo' => $todo,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="todo_delete", methods={"GET", "POST", "DELETE"})
     * @param Request $request
     * @param Todo $todo
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Request $request, Todo $todo) {

        if ($this->apiHelper->checkHeader($request, 2)) {

            $em = $this->getDoctrine()->getManager();
            $em->remove($todo);
            $em->flush();

            return $this->json(['result' => true, 'status'=>201], 201);
        }
        if ($this->isCsrfTokenValid('delete'.$todo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($todo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('todo_index');
    }
}
