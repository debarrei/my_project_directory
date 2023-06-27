<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Task;

use function PHPSTORM_META\type;

class TaskController extends AbstractController
{
    private $em;

    //Constructor
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    #[Route('tasks', name: 'all_tasks')]
    public function index(): Response
    {
        $tasks=$this->em->getRepository(Task::class)->findAll();
        var_dump($tasks);
        return new Response("muestra todas las tareas");
    }

    #[Route('/new-task', name: 'create_task')]
    public function new(): Response
    {
        $task = new Task();
        $task->setTitulo('Crear un controlador para la entidad Tasko');
        $task->setDescripcion('Crear cada uno de los métodos necesarios para el CRUD de la entidad Tasko');

        //Aquí le estamos diciendo a Doctrine que queremos guardar el nuevo tasko (sin hacer la query todavía)
        $this->em->persist($task);

        // ejecutamos la query, por ejemplo, el insertar. 
        $this->em->flush();

        return new Response('Guarda la task con id '.$task->getId());
    }

    #[Route('/show-task/{id}', name: 'show_task', methods:['GET'])]
    public function show(Task $task): Response
    {
        var_dump($task);
        // return new Response('MostrResponseada la task con id '.$task->getId());
        return new JsonResponse(['title'=>$task->getTitulo(),'descripcion'=>$task->getDescripcion()]);
        
    }

    #[Route('/other-show-task/{id}', name: 'other_show_task')]
    public function otherShow($id): Response
    {
        $task=$this->em->getRepository(Task::class)->findById($id);
        var_dump($task);
        return new Response('mostrada la tarea');
    }

    #[Route('/delete-task/{id}', name:'delete_task')]
    public function delete(Task $task): Response
    {
        $this->em->remove($task);
        $this->em->flush();
        // $task=$this->em->getRepository(Task::class)->find($id);
        // $this->em->remove($task);
        // $this->em->flush();
        return new Response('borrada la tarea correctamente');
    }

    #[Route('/update-task/{id}', name:'update_task')]
    public function update(Task $task): Response
    {
        $task->setTitulo('cambio el titulo');
        $this->em->persist($task);
        $this->em->flush();
        // $task=$this->em->getRepository(Task::class)->find($id);
        // $this->em->remove($task);
        // $this->em->flush();
        return new Response('borrada la tarea correctamente');
    }
}
