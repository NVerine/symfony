<?php


namespace App\Controller;

use App\Service\Notify;
use ReflectionParameter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ControllerController extends AbstractController
{
    /**
     * @param $id
     * @param $class
     * @param Notify $notify
     * @param array $propriedades
     * @param array $ignorados
     * @return string
     */
    // renderizar dentro das páginas de edição
    protected function single($id, $class, Notify $notify, array $propriedades = array(), array $ignorados = array())
    {
        // se for em branco retornar antes de consultar no db
        if ($id == 0) {
            return "";
        }
        $ignorados = array_merge(array("__initializer__", "__cloner__", "__isInitialized__"), $ignorados);

        $pessoa = $this->getDoctrine()
            ->getRepository($class)
            ->find($id);

        if (empty($pessoa)) {
            $notify->addMessage($notify::TIPO_INFO, "Registro não encontrado");
            return "";
        }

        return $this->serialize($pessoa, $propriedades, $ignorados);
    }

    // renderizar dentro das páginas de pesquisa

    /**
     * @param $repository
     * @param Request $request
     * @param array $propriedades
     * @param array $ignorados
     * @return string
     * não invocar em updates cascade
     */
    protected function lista($repository, Request $request, array $propriedades = array(),
                             array $ignorados = array(), array $order = array())
    {
        $ignorados = array_merge(array("__initializer__", "__cloner__", "__isInitialized__"), $ignorados);
        $conteudo = $request->query->all();
        $campos_que_usam_like = array("nome");
        $result = $repository->createQueryBuilder('t');

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

        return $this->serialize($result, $propriedades, $ignorados);
    }

    /**
     * @param $id
     * @param ValidatorInterface $validator
     * @param Request $request
     * @param $classe
     * @return JsonResponse|\Symfony\Component\Validator\ConstraintViolationListInterface
     * @throws \ReflectionException
     */
    protected function persiste($id, ValidatorInterface $validator, Request $request, $classe)
    {
        $entityManager = $this->getDoctrine()->getManager();

        if ($id > 0) {
            $classe = $this->getDoctrine()
                ->getRepository($classe)
                ->find($id);
        } else {
            $classe = new $classe();
        }

        $conteudo = json_decode($request->getContent(), true);
        $dependentes = array();

        foreach ($conteudo as $k => $v) {
            if (!$this->setpropriedades($classe, $k, $v)) {
                return JsonResponse::fromJsonString('"Este metodo não deve ser implementado usando ControllerController"',
                    200, array('Symfony-Debug-Toolbar-Replace' => 1)
                );
            }
        }

        $errors = $validator->validate($classe);
        if (count($errors) > 0) {
            return $errors;
        }

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($classe);

        foreach ($dependentes as $k => $v) {
            $get = "get" . ucfirst($k);
            foreach ($classe->$get() as $t) {
                dd($t);
            }
            $func = "add" . ucfirst($k);
            $refParam = new ReflectionParameter(array($classe, $func), 0);
            $export = ReflectionParameter::export(
                array(
                    $refParam->getDeclaringClass()->name,
                    $refParam->getDeclaringFunction()->name
                ),
                $refParam->name,
                true
            );

            $type = explode(' ', $export);
            foreach ($v as $r) {
                if (!empty($r["id"])) {
                    $classe2 = $this->getDoctrine()
                        ->getRepository($type[4])
                        ->find($r["id"]);
                } else {
                    $classe2 = new $type[4]();
                }

//                foreach ($r as $kk => $vv){
//                    $this->setpropriedades($classe2, $kk, $vv);
//                }
//                // tell Doctrine you want to (eventually) save the Product (no queries yet)
//                $entityManager->persist($classe2);
//                $classe->$func($classe2);
            }
        }

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        return JsonResponse::fromJsonString('"funfou"',
            200, array('Symfony-Debug-Toolbar-Replace' => 1)
        );
    }

    /**
     * @param $classe
     * @param $chave
     * @param $valor
     * @return bool
     */
    private function setpropriedades(&$classe, $chave, $valor)
    {
        if ($chave == 'id') {
            return true;
        }
        // toda data precisa chegar no formato dd-mm-yyyy
        if (strpos($chave, 'data') !== false) {
            $valor = \DateTime::createFromFormat('d-m-Y', $valor);
        }

        $func = "set" . ucfirst($chave);
        if (method_exists($classe, $func)) {
            $classe->$func($valor);
        } else {
            return false;
        }

        return true;
    }

    /**
     * @param $obj
     * @param array $propriedades
     * @param array $ignorados
     * @return string
     */
    private function serialize($obj, array $propriedades = array(), array $ignorados = array())
    {
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
            (empty($propriedades)) ?: AbstractNormalizer::ATTRIBUTES => $propriedades,
            (empty($ignorados)) ?: AbstractNormalizer::IGNORED_ATTRIBUTES => $ignorados
        ];
        $normalizer = new ObjectNormalizer(
            null, null, null,
            null, null, null, $defaultContext
        );

        $serializer = new Serializer([$normalizer], [$encoder]);

        return $serializer->serialize(
            $obj,
            'json'
        );
    }
}