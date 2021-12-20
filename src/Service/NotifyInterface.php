<?php


namespace App\Service;


interface NotifyInterface
{
    public function addMessage($type, $text);
    public function newReturn($data);
}