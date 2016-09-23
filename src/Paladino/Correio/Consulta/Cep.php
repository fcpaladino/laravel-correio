<?php
namespace Paladino\Correio\Consulta;


use Paladino\Correio\Config\Config;
use Paladino\Correio\Libs\Curl;
use Paladino\Correio\Libs\Helpers;


class Cep
{

    private $cep;

    public function __construct($cep)
    {
        if(!is_null($cep)) {
            $this->cep = Helpers::remove_mascara_cep($cep);
        }

    }

    public function get()
    {
        return $this->getData();
    }

    private function getData(){

        $config = Config::get('host');

        $data = Curl::send($config['busca-cep'], [
            'cepEntrada' => $this->cep,
            'tipoCep'    =>'',
            'cepTemp'    =>'',
            'metodo'     =>'buscarCep',
        ]);

        return $this->trataData($data);
    }

    private function trataData($html){

        include(__DIR__ . '../../Vendor/phpQuery.php');

        \phpQuery::newDocumentHTML($html, $charset = 'utf-8');

        $div = pq('#frmCep > div');

        $resultado = [];

        foreach($div as $item){
            if(pq($item)->is('.caixacampobranco') || pq($item)->is('.caixacampoazul')){

                $dados = [];

                $logradouro = null;

                if(count(pq('.resposta:contains("Endereço: ") + .respostadestaque:eq(0)',$item)))
                    $logradouro = trim(pq('.resposta:contains("Endereço: ") + .respostadestaque:eq(0)',$item)->text());
                else
                    $logradouro = trim(pq('.resposta:contains("Logradouro: ") + .respostadestaque:eq(0)',$item)->text());


                $log_arr = explode('- até', $logradouro);
                $dados['logradouro'] = isset($log_arr[0]) ? trim($log_arr[0]) : $logradouro;

                $dados['bairro']    = trim(pq('.resposta:contains("Bairro: ") + .respostadestaque:eq(0)',$item)->text());
                $dados['cep']       = trim(pq('.resposta:contains("CEP: ") + .respostadestaque:eq(0)',$item)->text());

                $localidade = explode('/', trim(pq('.resposta:contains("Localidade") + .respostadestaque:eq(0)',$item)->text()));

                $dados['cidade']    = trim($localidade[0]);
                $dados['uf']        = trim($localidade[1]);

                $resultado = $dados;
            }
        }

        return $resultado;

    }

}