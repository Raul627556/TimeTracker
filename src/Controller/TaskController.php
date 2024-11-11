<?php

namespace App\Controller;

use App\DTO\TaskDTO;
use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use App\Service\ApiClient;
use App\Service\ApiClientDelete;
use App\Service\ApiClientGet;
use App\Service\ApiClientPost;
use App\Service\TaskService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class TaskController extends AbstractController
{

    private TaskRepository $taskRepository;
    private SerializerInterface $serializer;
    private ApiClientGet $apiClientGet;
    private ApiClientPost $apiClientPost;
    private ApiClientDelete $apiClientDelete;


    public function __construct(
        TaskRepository $taskRepository,
        SerializerInterface $serializer,
        ApiClientGet $apiClientGet,
        ApiClientPost $apiClientPost,
        ApiClientDelete $apiClientDelete
    )
    {
        $this->taskRepository = $taskRepository;
        $this->serializer = $serializer;
        $this->apiClientGet = $apiClientGet;
        $this->apiClientPost = $apiClientPost;
        $this->apiClientDelete = $apiClientDelete;
    }

    /**
     * @Route("/task", name="task_index")
     */
    public function index(Request $request): Response
    {
        $taskDTO = new TaskDTO();
        $form = $this->createForm(TaskType::class, $taskDTO);
        $form->handleRequest($request);

        return $this->render('task/index.html.twig', [

            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/task/tasksInProgress", name="tasks_in_progress", methods={"GET"})
     */
    public function tableTasksInProgress(): Response
    {
        $response = $this->apiClientGet->request('task/tasksInProgress');

        $tasksData = json_decode($response->getContent(), true);

        $tasks =  $this->serializer->denormalize($tasksData, Task::class . '[]');
        $html = $this->renderView('task/tables/tasks_in_progress_table.html.twig', [
            'active_tasks' => $tasks,
        ]);

        return new Response($html);
    }

    /**
     * @Route("/task/summaryOfTodaysTasks", name="summary_of_todays_tasks", methods={"GET"})
     */
    public function tableSummary_of_todays_tasks(): Response
    {
        $response = $this->apiClientGet->request('task/summaryOfTodaysTasks');

        $data = json_decode($response->getContent(), true);

        $html = $this->renderView('task/tables/summary_of_todays_tasks_table.html.twig', [
            'tasks_grouped_by_name' => $data["tasks_grouped_by_name"],
            'total_time_today' => $data["total_time_today"],
        ]);

        return new Response($html);
    }

    /**
     * @Route("/task/start", name="task_start", methods={"POST"})
     */
    public function start(Request $request): Response
    {
        $response = $this->apiClientPost->request('task/start', $request->getContent());
        $data = json_decode($response->getContent(), true);

        return $this->json([
            'message' => $data["message"],
            'status' => $data["status"],
        ]);
    }

    /**
     * @Route("/task/stop/{id}", name="task_stop", methods={"POST"})
     */
    public function stop(int $id): Response
    {
        $response = $this->apiClientPost->request('task/stop/' . $id);
        $data = json_decode($response->getContent(), true);

        return $this->json([
            'message' => $data["message"],
            'status' => $data["status"],
        ]);
    }
}
