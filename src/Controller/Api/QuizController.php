<?php


namespace App\Controller\Api;


use App\Controller\ControllerController;
use App\Entity\Questions;
use App\Entity\Quiz;
use App\Entity\QuizQuestions;
use App\Repository\QuizRepository;
use App\Service\Notify;
use Doctrine\ORM\EntityManager;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/test")
 */
class QuizController extends ControllerController
{
    /**
     * @Route("/", name="api_test_index", methods={"GET"})
     */
    public function index(Request $request, QuizRepository $quizRepository, Notify $notify): Response
    {
        return JsonResponse::fromJsonString(
            $notify->newReturn(parent::lista($quizRepository, $request, ["id", "title", "description", "public"])),
            200, array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }

    /**
     * @Route("/{id}", name="api_test_show", methods={"GET"})
     * @throws Exception
     */
    public function show($id, Notify $notify): Response
    {
        return JsonResponse::fromJsonString(
            $notify->newReturn(parent::single($id, Quiz::class, $notify)),
            200, array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }

    /**
     * @Route("/{id}/edit", name="api_test_edit", methods={"POST"})
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
                ->getRepository(Quiz::class)
                ->find($id);

            // limpa a lista antes de salvar novamente
            $lista = $this->getDoctrine()
                ->getRepository(QuizQuestions::class)
                ->findBy(array("quiz" => $id));

            foreach ($lista as $r){
                $entityManager->remove($r);
            }
        }
        else {
            $item = new Quiz();
        }

        $item->setPublic(isset($conteudo["public"]) ? $conteudo["public"] : false);
        $item->setTitle($conteudo["title"]);
        $item->setDescription($conteudo["description"]);

        $errors = $validator->validate($item);
        if (count($errors) > 0) {
            throw new \Exception($errors);
        }

        $entityManager->persist($item);

        if(!empty($conteudo["questions"])) {
            foreach ($conteudo["questions"] as $c) {
                $question = $this->getDoctrine()
                    ->getRepository(Questions::class)
                    ->find($c["id"]);

                $questions = new QuizQuestions();
                dump($c);
                $questions->setQuestion($question);
                $questions->setQuiz($item);
                $questions->setWeight($c["weight"]);
                $questions->setOrdem($c["ordem"]);
                $entityManager->persist($questions);
            }
        }

        $entityManager->flush();

        $notify->addMessage($notify::TIPO_SUCCESS, "Test updated");
        return JsonResponse::fromJsonString(
            $notify->newReturn($item->getId()),
            200, array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }
}