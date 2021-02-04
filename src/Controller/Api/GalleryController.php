<?php

namespace App\Controller\Api;

use App\Controller\ControllerController;
use App\Service\BlobService;
use App\Service\Notify;
use Exception;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

/**
 * Class BlobsController
 * @package App\Controller
 * @Route("/api/gallery")
 */
class GalleryController extends ControllerController
{

    /**
     * GalleryController constructor.
     * @param Notify $notify
     */
    public function __construct(Notify $notify)
    {
        $this->notify = $notify;
    }

    /**
     * por padrão sempre pesquisa dentro do container images
     * ex: folder = pessoas, id = 1
     * @Route("/{folder}/{id}", name="_api_gallery_index")
     * @param BlobService $storage
     * @param $folder
     * @param $id
     * @return JsonResponse
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function index(BlobService $storage, $folder, $id): JsonResponse
    {
        $folder = $folder.'/'.$id;
        $blobListOptions = new ListBlobsOptions();
        $blobListOptions->setPrefix($folder);
        $arr = $storage->allBlobs('images', $blobListOptions);
        $temp = [];
        foreach ($arr as $r){
            $temp[] = ['url' => $r->getUrl(), 'nome' => str_replace($folder.'/' , '', $r->getName())];
        }
        return $this->notifyReturn(parent::serialize($temp));
    }

    /**
     * @Route("/{folder}/{id}/edit", name="_api_gallery_edit", methods={"POST"})
     * @param $id
     * @param $folder
     * @param Request $request
     * @param BlobService $storage
     * @return JsonResponse
     * @throws Exception
     */
    public function edit($id, $folder, Request $request, BlobService $storage): JsonResponse
    {
        $conteudo = json_decode($request->getContent(), true);
        $prefix = $folder.'/'.$id.'/';

        if(isset($conteudo["deletar"])){
            $storage->delete($prefix.$conteudo["nome"]);
            $this->notify->addMessage($this->notify::TIPO_SUCCESS, "Imagem deletada com sucesso");
            return $this->notifyReturn($id);
        }

        BlobService::base64ToImage($conteudo["file"], $conteudo["nome"]);
        $storage->upload($conteudo["nome"], 'images', $prefix);
        $this->notify->addMessage($this->notify::TIPO_SUCCESS, "Imagem salva com sucesso");
        return $this->notifyReturn($id);
    }
}