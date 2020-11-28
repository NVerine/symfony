<?php

namespace App\Controller\Api;

use App\Controller\ControllerController;
use App\Entity\Disciplina;
use App\Entity\Questions;
use App\Entity\QuestionsOPT;
use Doctrine\ORM\EntityManager;
use Exception;
use App\Repository\QuestionsRepository;
use App\Service\Notify;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/questions")
 */
class QuestionsController extends ControllerController
{
    /**
     * @Route("/", name="api_questions_index", methods={"GET"})
     */
    public function index(Request $request, QuestionsRepository $questionsRepository, Notify $notify): Response
    {
        return JsonResponse::fromJsonString(
            $notify->newReturn(parent::lista($questionsRepository, $request, ["id", "title", "description", "tipo"])),
            200, array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }

    /**
     * @Route("/show", name="api_questions_list", methods={"GET"})
     */
    public function list(Request $request, QuestionsRepository $questionsRepository, Notify $notify): Response
    {
        return JsonResponse::fromJsonString(
            $notify->newReturn(parent::lista($questionsRepository, $request, [], ["answer", "disciplina" => "questions"])),
            200, array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }

    /**
     * @Route("/{id}", name="api_questions_show", methods={"GET"})
     * @throws Exception
     */
    public function show($id, Notify $notify): Response
    {
        return JsonResponse::fromJsonString(
            $notify->newReturn(parent::single($id, Questions::class, $notify, [], ["quizQuestions"])),
            200, array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }

    /**
     * @Route("/{id}/edit", name="api_questions_edit", methods={"POST"})
     * @throws \Exception
     */
    public function edit($id, ValidatorInterface $validator, Request $request, Notify $notify): Response
    {
        $conteudo = json_decode($request->getContent(), true);

        /**
         * @var $entityManager EntityManager
         */
        $entityManager = $this->getDoctrine()->getManager();

        if (!empty($id)) {
            $item = $this->getDoctrine()
                ->getRepository(Questions::class)
                ->find($id);
        }
        else {
            $item = new Questions();
        }

        $disciplina = $this->getDoctrine()
            ->getRepository(Disciplina::class)
            ->find($conteudo["disciplina"]["id"]);

        $item->setDescription($conteudo["description"]);
        $item->setTitle($conteudo["title"]);
        $item->setTipo($conteudo["tipo"]);
        $item->setAnswer($conteudo["answer"]);
        $item->setDisciplina($disciplina);

        $errors = $validator->validate($item);
        if (count($errors) > 0) {
            throw new \Exception($errors);
        }

        $entityManager->persist($item);

        if(!empty($conteudo["opt"])) {
            foreach ($conteudo["opt"] as $c) {
                if (!empty($c["id"])) {
                    $option = $this->getDoctrine()
                        ->getRepository(QuestionsOPT::class)
                        ->find($c["id"]);
                    $notify->addMessage($notify::TIPO_INFO, "Updating option");
                } else {
                    $option = new QuestionsOPT();
                    $notify->addMessage($notify::TIPO_INFO, "Inserting option");
                }

                if (isset($c["exclui"]) && $c["exclui"]) {
                    // Now if we remove it, it will set the deletedAt field to the actual date
                    $entityManager->remove($option);
                    $notify->addMessage($notify::TIPO_INFO, "Removing option");
                } else {
                    $option->setQuestion($item);
                    $option->setText($c["text"]);
                    $option->setIsanswer($c["isAnswer"]);
                    $entityManager->persist($option);
                }
            }
        }

        $entityManager->flush();

        $notify->addMessage($notify::TIPO_SUCCESS, "Question updated");
        return JsonResponse::fromJsonString(
            $notify->newReturn($item->getId()),
            200, array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }
}