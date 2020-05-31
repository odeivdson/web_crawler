<?php

namespace App\Http\Controllers;

use App\CrawlerDataFormat;
use App\Http\Requests\CrawlerDataFormatDetailRequest;
use App\Http\Requests\CrawlerDataFormatRequest;
use GuzzleHttp\Client;
use http\Exception;
use Illuminate\Http\Request;
use Goutte;
use Symfony\Component\DomCrawler\Crawler;

class CrawlerController extends Controller
{
    protected $urlBase = 'https://seminovos.com.br/';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(CrawlerDataFormatRequest $request)
    {
        $req = $this->processaRequest($request);

        $pageUrl = $this->urlBase . $req;

        $errors = array();
        $result = CrawlerDataFormat::makeWebRequest($pageUrl, $errors);
        if(empty($errors)) {
            $response['content'] = CrawlerDataFormat::parseItemsListJson($result);
            $response['Status'] = 'success';
            $response['Message'] = 'Registros localizados com sucesso';
        } else {
            $response['Errors'] = $errors;
            $response['Status'] = 'error';
            $response['Message'] = "Ooops, não foi possível encontrar registros com os dados informados.";
        }
        return response()->json($response);
    }

    private function processaRequest($request)
    {
        $parametros = '';

        // Verifica se foi informado a paginação
        $request->has('page') ? $page = '?page=' . $request->page : $page = '';

        if($request->tipo_veiculo) {
            $parametros .= $request->tipo_veiculo;
        } else {
            $parametros .= 'carro';
        }


        // Se não for informado a marca do veículo, faz a busca com base no tipo_veiculo
        if(!$request->marca_veiculo) {
            $parametros .= $page;
            return $parametros;
        } else {
            $parametros .= '/' . $request->marca_veiculo;
        }

        if($request->modelo_veiculo) {
            $parametros .= '/' . $request->modelo_veiculo;
        }

        if($request->versao_veiculo) {
            $parametros .= '/versao-' . $request->versao_veiculo;
        }

        // Validação de ano do veículo
        if($request->ano_veiculo_min) {
            $parametros .= '/ano-' . $request->ano_veiculo_min;
            if($request->ano_veiculo_max) {
                $parametros .= '-' . $request->ano_veiculo_max;
            }
        }

        if($request->ano_veiculo_max && !$request->ano_veiculo_min) {
            $parametros .= '/ano-' . $request->ano_veiculo_max;
        }

        // Validação de Preço mínimo e máximo
        if($request->preco_veiculo_min) {
            $parametros .= '/preco-' . $request->preco_veiculo_min;
            if($request->preco_veiculo_max) {
                $parametros .= '-' . $request->preco_veiculo_max;
            }
        }

        if($request->preco_veiculo_max && !$request->preco_veiculo_min) {
            $parametros .= '/preco-' . $request->preco_veiculo_max;
        }

        $parametros .= $page;
        return $parametros;
    }

    public function detalhe(CrawlerDataFormatDetailRequest $request)
    {
        $request->id_detalhe ? $req = $request->id_detalhe : $req = 'carro';
        $pageUrl = $this->urlBase . $req;

        $errors = array();
        $result = CrawlerDataFormat::makeWebRequest($pageUrl, $errors);
        if(empty($errors)) {
            $response['content'] = CrawlerDataFormat::parseItemDetailJson($result);
            $response['Status'] = 'success';
            $response['Message'] = 'Registros localizados com sucesso';
        } else {
            $response['Errors'] = $errors;
            $response['Status'] = 'error';
            $response['Message'] = "Ooops, não foi possível encontrar registros com os dados informados.";
        }
        return response()->json($response);
    }
}
