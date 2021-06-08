<?php


namespace App\Traits;


use Symfony\Component\HttpFoundation\JsonResponse;

trait Response
{
    /**
     * @param mixed $items
     * @param array|null $header
     * @param array|null $groups
     * @return JsonResponse
     * padroniza o retorno
     */
    private function response($items, array $header = null, array $groups = null): JsonResponse
    {
        return $this->notifyReturn(
            parent::serialize(
                [
                    "headers" => $header,
                    "items" => $items
                ],
                $groups ?? []
            )
        );
    }
}