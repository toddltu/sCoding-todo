<?php
// http://www.inanzzz.com/index.php/post/nx2b/validating-serialising-and-mapping-json-request-to-model-classes

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoType;
use App\Repository\TodoRepository;
use App\Service\ApiHelper;
use Doctrine\ORM\EntityManager;
use JsonSchema\Validator as JsonValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api")
 */
class ApiController extends AbstractController
{

    private $apiHelper;
    protected $serializer;

    /**
     * ApiController constructor.
     * @param ApiHelper $apiHelper
     */
    public function __construct(ApiHelper $apiHelper, SerializerInterface $serializer)
    {
        $this->apiHelper = $apiHelper;
        $this->serializer = $serializer;
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
     */
    public function create(Request $request, ValidatorInterface $validator)
    {
        $todo = new Todo();
        if ($this->apiHelper->checkHeader($request)) {
            $data = json_decode($request->getContent(), true);

            /** Form Validator */
            $form = $this->createForm(TodoType::class, $todo);
            $form->submit($data);

            if(!$form->isValid()) {
                $result = $this->apiHelper->getErrorMessages($form);
                return $this->json(['errors' => $result, 'status' => 400], 400);
            }
            return $this->json(['wtf' => $form->getData(), 'status' => 200], 200);

            /** @var Todo $payload */
            $payload = $this->serializer->deserialize($request->getContent(), Todo::class, 'json');
            $errors = $validator->validate($payload);
            if($errors->count()) {
                /*$error_ = [];
                /** @var ConstraintViolation $violation * /
                foreach ($errors as $violation) {
                    $error_[] = $violation->getMessage();
                }*/
                return $this->json(['errors' => $errors, 'status' => 400], 400);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($payload);
            $em->flush();

            return $this->json(['ok' => true , 'id'=> $payload->getId(),'status' => 201], 201);
        }

        $todo = new Todo();
    }
}
