<?php

namespace App\Service;

use Exception;
use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Blob\Models\Blob;
use MicrosoftAzure\Storage\Blob\Models\Container;
use MicrosoftAzure\Storage\Blob\Models\CreateBlockBlobOptions;
use MicrosoftAzure\Storage\Blob\Models\DeleteBlobOptions;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;

/**
 * Class BlobService
 * @package App\Service
 */
class BlobService
{
    private $blobClient;

    /**
     * BlobService constructor.
     */
    public function __construct()
    {
        $this->blobClient = BlobRestProxy::createBlobService($_SERVER['AZURE_STORAGE_CONNECTION_STRING']);
    }

    /**
     * @return Container[]
     * @throws Exception
     */
    public function allContainers(): array
    {
        try {
            $container_list = $this->blobClient->listContainers();
            return $container_list->getContainers();

        } catch (ServiceException $exception) {
            throw new Exception('failed to get all containers: ' . $exception->getCode() . ':' . $exception->getMessage());
        }
    }

    /**
     * @param string $container
     * @param ListBlobsOptions|null $blobListOptions
     * @return Blob[]
     * @throws Exception
     */
    public function allBlobs(string $container, ListBlobsOptions $blobListOptions = null): array
    {
        try {
            $result = $this->blobClient->listBlobs($container, $blobListOptions);
            return $result->getBlobs();

        } catch (ServiceException $exception) {
            throw new Exception('failed to get all blobs: ' . $exception->getCode() . ':' . $exception->getMessage());
        }
    }

    /**
     * @param string $file localização
     * @param string $container
     * @param string $prefix
     * @throws Exception
     */
    public function upload(string $file, $container = 'images', $prefix = '')
    {
        try {
            $content = file_get_contents($file);
            $option = new CreateBlockBlobOptions();
            $option->setContentType(mime_content_type($file));

            $this->blobClient->createBlockBlob($container, $prefix.$file, $content, $option);

        } catch (ServiceException $exception) {
            throw new Exception('failed to upload the file: ' . $exception->getCode() . ':' . $exception->getMessage());
        } finally {
            unlink($file);
        }
    }

    /**
     * @param $blobName
     * @param string $container
     * @param DeleteBlobOptions|null $options
     * @throws Exception
     */
    public function delete($blobName, $container = 'images', DeleteBlobOptions $options = null)
    {
        try {
            $this->blobClient->deleteBlob($container, $blobName, $options);
        } catch (ServiceException $exception) {
            throw new Exception('failed to delete the file: ' . $exception->getCode() . ':' . $exception->getMessage());
        }
    }

    /**
     * @param $base64_string
     * @param $nome
     * @return mixed
     */
    public static function base64ToImage($base64_string, $nome) {
        $file = fopen($nome, "w");
        $data = explode(',', $base64_string);
        fwrite($file, base64_decode($data[1]));
        fclose($file);

        return $file;
    }
}