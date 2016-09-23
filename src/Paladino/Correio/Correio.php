<?php
namespace Paladino\Correio;

use Paladino\Correio\Consulta\Cep;
use Paladino\Correio\Consulta\Frete;
use Paladino\Correio\Consulta\Rastreiar;

class Correio{

    /**
     * @return Cep
     */
    public static function cep($cep){
        return new Cep($cep);
    }


    /**
     * @return Frete
     */
    public static function frete(){
        return new Frete();
    }


    /**
     * @return Rastreiar
     */
    public static function rastreiar($codigo){
        return new Rastreiar($codigo);
    }

}