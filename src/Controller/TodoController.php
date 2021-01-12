<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository\TodoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TodoController extends AbstractController
{
    private TodoRepository $todoRepository;

    public function __construct(TodoRepository $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    /**
     * @Route("/todo/list", name="todo_list")
     */
    public function list(): Response
    {
        return $this->json([
            'todos' => $this->todoRepository->findAllAsArray()
        ]);
    }
}
