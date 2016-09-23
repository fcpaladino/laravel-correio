<?php
/**
 * Created by PhpStorm.
 * User: Filipe Paladino
 * Date: 22/09/2016
 * Time: 13:14
 */

namespace Paladino\Correio\Consulta;


use Paladino\Correio\Libs\Helpers;

class Configuracao
{

    private $configuracao = [];

    private $tipos_frete = [
        '40010' => 'Sedex',
        '40045' => 'Sedex a cobrar',
        '40215' => 'Sedex 10',
        '40290' => 'Sedex hoje',
        '41106' => 'Pac',
        '41068' => 'Pac contrato',
        '40096' => 'Sedex contrato',
        '81019' => 'E-sedex'
    ];

    private $tipos_formatos = [
        1 => 'caixa',
        2 => 'rolo',
        3 => 'envelope'
    ];

    private $cep;

    private $codigo;



    public function getConfiguracao()
    {
        return array_merge([
            'nCdEmpresa'          => '',
            'sDsSenha'            => '',
            'nCdServico'          => '40010,41106',
            'sCepOrigem'          => '',
            'sCepDestino'         => '',
            'nVlPeso'             => 1,
            'nCdFormato'          => '1',
            'nVlComprimento'      => '16',
            'nVlAltura'           => '11',
            'nVlLargura'          => '11',
            'nVlDiametro'         => '0',
            'sCdMaoPropria'       => 'N',
            'nVlValorDeclarado'   => 0,
            'sCdAvisoRecebimento' => 'N',
            'sDtCalculo'          => date('d/m/Y'),

        ], $this->configuracao);
    }

    protected function setConfig($key, $value)
    {
        $this->configuracao[$key] = $value;
    }





    public function cep($value)
    {
        $this->cep = (string)Helpers::remove_mascara_cep($value);
        return $this;
    }

    public function codigo($value)
    {
        $this->codigo = (string)$value;
        return $this;
    }

    public function origem($cep)
    {
        $this->setConfig('sCepOrigem', (string)Helpers::remove_mascara_cep($cep));
        return $this;
    }

    public function destino($cep)
    {
        $this->setConfig('sCepDestino', (string)Helpers::remove_mascara_cep($cep));
        return $this;
    }

    public function servico($args)
    {
        $ops = is_array($args) ? implode(',', $args) : $args;

        $this->setConfig('nCdServico', (string)$ops);
        return $this;
    }

    public function peso($peso)
    {
        $this->setConfig('nVlPeso', (float)$peso);
        return $this;
    }

    public function formato($value)
    {
        $this->setConfig('nCdFormato', (string)$value);
        return $this;
    }

    public function comprimento($value)
    {
        $this->setConfig('nVlComprimento', (float)$value);
        return $this;
    }

    public function altura($value)
    {
        $this->setConfig('nVlAltura', (float)$value);
        return $this;
    }

    public function largura($value)
    {
        $this->setConfig('nVlLargura', (float)$value);
        return $this;
    }

    public function diametro($value)
    {
        $this->setConfig('nVlDiametro', (float)$value);
        return $this;
    }

    public function entregaPessoal($value)
    {
        $val = (bool)$value ? "S" : "N";
        $this->setConfig('sCdMaoPropria', (string)$val);
        return $this;
    }

    public function valor($value)
    {
        $this->setConfig('nVlValorDeclarado', (float)$value);
        return $this;
    }

    public function recebimento($value)
    {
        $val = (bool)$value ? "S" : "N";
        $this->setConfig('sCdAvisoRecebimento', (string)$val);
        return $this;
    }

    public function data($value)
    {
        $this->setConfig('sDtCalculo', $value);
        return $this;
    }





    protected function getCep()
    {
        return $this->cep;
    }

    protected function getCodigo()
    {
        return $this->codigo;
    }

    protected function getFrete($code){
        return isset($this->tipos_frete[$code]) ? $this->tipos_frete[$code] : null;
    }

    protected function getFormato($code){
        return isset($this->tipos_formatos[$code]) ? $this->tipos_formatos[$code] : null;
    }

}