<?php
namespace Paladino\Correio\Libs;


class Helpers
{

    static public function remove_mascara_cep($cep){
        return preg_replace("/[^0-9]/", '', $cep);
    }

    static public function remove_espaco_duplo($texto){
        return preg_replace('/( )+/', ' ', $texto);
    }


}