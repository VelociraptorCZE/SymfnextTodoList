<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Todo;
use App\Repository\TodoRepository;
use App\Transformer\TodoTransformer;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class TodoController extends AbstractController
{
    private TodoRepository $todoRepository;
    private TodoTransformer $todoTransformer;
    private EntityManagerInterface $entityManager;

    public function __construct(
        TodoRepository $todoRepository,
        TodoTransformer $todoTransformer,
        EntityManagerInterface $entityManager
    ) {
        $this->todoRepository = $todoRepository;
        $this->todoTransformer = $todoTransformer;
        $this->entityManager = $entityManager;
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

    /**
     * @Route("/todo/remove/{id}", name="todo_remove")
     * @param int $id
     * @return Response
     */
    public function remove($id): Response
    {
        try {
            $todo = $this->todoRepository->find($id);
            $this->entityManager->remove($todo);
            $this->entityManager->flush();

            return $this->json(['result' => true]);
        } catch (Throwable $e) {
            return $this->json(['result' => false]);
        }
    }

    /**
     * @Route("/todo/create", name="todo_create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        try {
            $todo = new Todo;
            $todo->setCreatedAt(new DateTimeImmutable);
            $todo->setDescription($request->request->get('description'));

            $this->entityManager->persist($todo);
            $this->entityManager->flush();

            return $this->json([
                'todo' => $this->todoTransformer->transform($todo),
                'result' => true
            ]);
        } catch (Throwable $e) {
            return $this->json(['result' => false]);
        }
    }
}
