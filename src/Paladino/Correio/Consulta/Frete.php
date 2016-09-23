<?php
namespace Paladino\Correio\Consulta;


use Paladino\Correio\Config\Config;


class Frete extends Configuracao
{

    public function __construct()
    {
    }

    public function get()
    {
        $this->getConfiguracao();
        return $this->getData();
    }

    private function getData()
    {

        try{

            $config = Config::get('host');

            $resultado          = [];
            $data               = [];


            if(class_exists('SoapClient')){
                $soap = new \SoapClient($config['calcula-frete'], [
                    'trace'              => true,
                    'exceptions'         => true,
                    'compression'        => SOAP_COMPRESSION_ACCEPT | SOAP_COMPRESSION_GZIP,
                    'connection_timeout' => 1000
                ]);

                $CalcPrecoPrazoData = $soap->CalcPrecoPrazoData($this->getConfiguracao());
                $data               = $CalcPrecoPrazoData->CalcPrecoPrazoDataResult->Servicos->cServico;

                if(!is_array($data)){
                    $resultado[] = (array)$data;
                } else {
                    $resultado    = $data;
                }

                return $this->trataData($resultado);
            }

            return "A classe 'SoapClient' não existe. Verifique se ela encontra-se habilitada.";

        }catch (Exception $e){

            return [];

        }


    }

    private function trataData($resultado)
    {
        $retorno = [];

        foreach($resultado as $consulta){
            $consulta = (array) $consulta;

            $retorno[] = [
                'codigo'             => $consulta['Codigo'],
                'tipo'               => $this->getFrete($consulta['Codigo']),
                'valor'              => (float) str_replace(',','.',$consulta['Valor']),
                'prazo'              => (int) str_replace(',','.',$consulta['PrazoEntrega']),
                'mao_propria'        => (float) str_replace(',','.',$consulta['ValorMaoPropria']),
                'aviso_recebimento'  => (float) str_replace(',','.',$consulta['ValorAvisoRecebimento']),
                'valor_declarado'    => (float) str_replace(',','.',$consulta['ValorValorDeclarado']),
                'entrega_domiciliar' => ($consulta['EntregaDomiciliar'] === 'S' ? true : false),
                'entrega_sabado'     => ($consulta['EntregaSabado'] === 'S' ? true : false),
                'erro'               =>  array('codigo'=> (real)$consulta['Erro'],'mensagem'=>$consulta['MsgErro']),
            ];
        }

        return $retorno;
    }

}