<?php


namespace App\Service;

class Notify
{
    const TIPO_ERROR = "danger";
    const TIPO_WARNING = "warning";
    const TIPO_INFO = "info";
    const TIPO_SUCCESS = "success";

    private $mensagens = array();

    public function addMessage($tipo, $texto){
        $this->mensagens[] = array(
            "tipo" => $tipo,
            "texto" => $texto
        );
    }

    /**
     * @param string $dados
     * @return string
     */
    public function newReturn(string $dados){
        if(empty($dados)){
            return '{"dados": "", "notify": '.json_encode($this->mensagens).'}';
        }
        return '{"dados":'.$dados.', "notify": '.json_encode($this->mensagens).'}';
    }
}