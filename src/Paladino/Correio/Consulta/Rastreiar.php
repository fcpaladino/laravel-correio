<?php
namespace Paladino\Correio\Consulta;


use Paladino\Correio\Config\Config;
use Paladino\Correio\Libs\Curl;
use Paladino\Correio\Libs\Helpers;

class Rastreiar
{

    private $codigo;


    public function __construct($codigo)
    {
        if(!is_null($codigo)) {
            $this->codigo = $codigo;
        }
    }

    public function get()
    {
        return $this->getData();
    }

    private function getData(){

        $config = Config::get('host');

        $data = Curl::send($config['rastrea-pedido'] . $this->codigo);

        return $this->trataData($data);
    }

    private function trataData($html){

        include(__DIR__ . '../../Vendor/phpQuery.php');

        \phpQuery::newDocumentHTML($html, $charset = 'utf-8');

        $retorno = [];

        $c = 0;
        foreach(pq('tr') as $tr){$c++;

            if(count(pq($tr)->find('td')) == 3 && $c > 1){
                $datahora = pq($tr)->find('td:eq(0)')->text();
                $local    = pq($tr)->find('td:eq(1)')->text();
                $status   = pq($tr)->find('td:eq(2)')->text();

                $arr_local = explode(' - ', $local);

                if(isset($arr_local[1]))
                    $cidade_uf = explode('/', $arr_local[1]);

                $retorno[] = [
                    'datahora'      => trim($datahora),
                    'local'         => trim($arr_local[0]),
                    'cidade'        => isset($cidade_uf[0]) ? trim(Helpers::remove_espaco_duplo($cidade_uf[0])) : null,
                    'estado'        => isset($cidade_uf[1]) ? trim(Helpers::remove_espaco_duplo($cidade_uf[1])) : null,
                    'status'        => trim($status),
                    'encaminhado'   => null
                ];

            }

            if(count(pq($tr)->find('td')) == 1 && $c > 1){
                $retorno[count($retorno)-1]['encaminhado'] = trim(Helpers::remove_espaco_duplo(pq($tr)->find('td:eq(0)')->text()));
            }

        }

        return $retorno;

    }
}