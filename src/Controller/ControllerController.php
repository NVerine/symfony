<?php


namespace App\Controller;

use App\Service\Notify;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;

class ControllerController extends AbstractController
{

    protected $entity;

    /**
     * @var ServiceEntityRepository
     */
    protected ServiceEntityRepository $repository;

    /**
     * @var Notify
     */
    protected Notify $notify;

    /**
     * @var EntityManager
     */
    protected EntityManager $em;

    protected $createdEntity;

    /**
     * @param $id
     * @param array $grupos
     * @param array $propriedades
     * @param array $ignorados
     * @return string
     * @throws ExceptionInterface
     * @deprecated
     */
    // renderizar dentro das páginas de edição
    protected function single($id, array $grupos = [], array $propriedades = [], array $ignorados = []): string
    {
        // se for em branco retornar antes de consultar no db
        if ($id == 0) {
            return "";
        }
        $ignorados = array_merge(array("__initializer__", "__cloner__", "__isInitialized__"), $ignorados);

        $pessoa = $this->getDoctrine()
            ->getRepository($this->entity)
            ->find($id);

        if (empty($pessoa)) {
            $this->notify->addMessage($this->notify::TIPO_INFO, "Registro não encontrado");
            return "";
        }

        return $this->serialize($pessoa, $grupos, $propriedades, $ignorados);
    }

    // renderizar dentro das páginas de pesquisa

    /**
     * @param Request $request
     * @param array $grupos
     * @param array $propriedades
     * @param array $ignorados
     * @param array $order
     * @return string
     * não invocar em updates cascade
     * @throws ExceptionInterface
     * @deprecated
     */
    protected function lista(Request $request, array $grupos = [], array $propriedades = [], array $ignorados = [], array $order = []): string
    {
        $ignorados = array_merge(array("__initializer__", "__cloner__", "__isInitialized__"), $ignorados);
        $conteudo = $request->query->all();
        $campos_que_usam_like = array("nome");
        $result = $this->repository->createQueryBuilder('t');

        foreach ($conteudo as $k => $v) {
            if (strpos($k, 'pesq_') !== false) {
                if (!in_array($k, array('pesq_offset', 'pesq_limite')) && !empty($v)) {
                    $key = str_replace('pesq_', '',
                        strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $k))
                    );
                    if (in_array($key, $campos_que_usam_like)) {
                        $v .= "%";
                    }
                    $result->andWhere("t.{$key} LIKE :{$key}")->setParameter($key, $v);
                }
            }
        }

        $limit = $request->query->get('pesq_limite');
        $pag = $request->query->get('pesq_offset');
        if (!empty($pag) && !empty($limit)) {
            $pag = $pag * $limit;
        }

        if (!empty($order)) {
            $result->orderBy('t.' . $order[0], $order[1]);
        }

        $result = $result->setFirstResult($pag)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        return $this->serialize($result, $grupos, $propriedades, $ignorados);
    }

    /**
     * @param string|null $json
     * @return JsonResponse
     */
    protected function notifyReturn(?string $json): JsonResponse
    {
        return JsonResponse::fromJsonString(
            $this->notify->newReturn($json), 200, array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }

    /**
     * @param $id
     */
    protected function getOrCreate($id)
    {
        $this->em = $this->getDoctrine()->getManager();

        if (!empty($id)) {
            $this->createdEntity = $this->getDoctrine()
                ->getRepository($this->entity)
                ->find($id);
        } else {
            $this->createdEntity = new $this->entity();
        }
    }

    /**
     * @param ValidatorInterface $validator
     * @param string $entityName
     * @return JsonResponse
     * @throws Exception
     */
    protected function insertOrUpdate(ValidatorInterface $validator, string $entityName) :JsonResponse
    {
        $errors = $validator->validate($this->createdEntity);
        if (count($errors) > 0) {
            throw new Exception($errors);
        }

        $this->em->persist($this->createdEntity);
        $this->em->flush();

        $this->notify->addMessage($this->notify::TIPO_SUCCESS, "{$entityName} salvo com sucesso");
        return $this->notifyReturn($this->createdEntity->getId());
    }

    /**
     * @param $obj
     * @param array $grupos
     * @param array $propriedades
     * @param array $ignorados
     * @return string
     * @throws ExceptionInterface
     */
    public function serialize($obj, array $grupos = [], array $propriedades = [], array $ignorados = []): string
    {
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
            (empty($propriedades)) ?: AbstractNormalizer::ATTRIBUTES => $propriedades,
            (empty($ignorados)) ?: AbstractNormalizer::IGNORED_ATTRIBUTES => $ignorados
        ];
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer(
            $classMetadataFactory, null, null,
            null, null, null, $defaultContext
        );

        $serializer = new Serializer([$normalizer], [$encoder]);

        // ['groups' => ['group1', 'group3']]
        if (!empty($grupos)) $grupos = ['groups' => $grupos]; // corrige array

        $obj = $serializer->normalize($obj, null, $grupos);
        return $serializer->serialize($obj, 'json');
    }
}