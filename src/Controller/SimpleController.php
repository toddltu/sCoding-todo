<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SimpleController extends AbstractController
{
    /**
     * @Route("/", name="simple_index", methods={"GET"})
     */
    public function index()
    {
        return $this->redirectToRoute('todo_index');
    }
}