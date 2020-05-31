<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class CrawlerDataFormat extends Model
{
    /**
     * Parse json string into array
     * @param string $result
     */
    public static function parseItemsListJson($result) {
        $response = '';
        $arr = [];
        $filter_img = '';
        try {
            $crawler = new Crawler($result);

            // Paginação
            if($crawler->filter('nav.pagination-container')->filter('.info')->count() > 0) {
                $page_info = $crawler->filter('nav.pagination-container')->filter('.info')->text();
                if($page_info) {
                    $arr['page_info'] = $page_info;
                }
            }

            $filter = $crawler->filter('div.anuncio-container');
            foreach ($filter as $i => $domElement) {
                $_crawler = new Crawler($domElement);

                if($_crawler->filter('figure img')->count() > 0) {
                    $filter_img = $_crawler->filter('figure img')->attr('src');
                }

                if($filter_img) {
                    $arr[$i]['img_url'] = $filter_img;
                }

                $filter_acessorios = $_crawler->filter('div .card-body .card-acessorios');
                $arr_acessorios = "";

                foreach ($filter_acessorios as $k=>$acessorios) {
                    $_acessorios = new Crawler($acessorios);
                    $arr_acessorios = $_acessorios->filter('div ')->text();
                }

                if($_crawler->filter('div .card-body')->count() > 0) {
                    $arr[$i]['id_detalhe'] = substr($_crawler->filter('div .card-body a')->attr('href'), 1);
                }

                if($_crawler->filter('div .card-body .card-header b')->count() > 0) {
                    $arr[$i]['descricao'] = $_crawler->filter('div .card-body .card-header b')->text();
                }

                if($_crawler->filter('div .value h4')->count() > 0) {
                    $arr[$i]['valor_evenda'] = $_crawler->filter('div .value h4')->text();
                }

                if($_crawler->filter('div .card-body .card-detalhes .ano span')->count() > 0) {
                    $arr[$i]['detalhes']['ano'] = $_crawler->filter('div .card-body .card-detalhes .ano span')->text();
                }

                if($_crawler->filter('div .card-body .card-detalhes .kilometragem span')->count() > 0) {
                    $arr[$i]['detalhes']['kilometragem'] = $_crawler->filter('div .card-body .card-detalhes .kilometragem span')->text();
                }

                if($_crawler->filter('div .card-body .card-detalhes .combustivel span')->count() > 0) {
                    $arr[$i]['detalhes']['combustivel'] = $_crawler->filter('div .card-body .card-detalhes .combustivel span')->text();
                }

                if($_crawler->filter('div .card-body .card-detalhes .cambio span')->count() > 0) {
                    $arr[$i]['detalhes']['cambio'] = $_crawler->filter('div .card-body .card-detalhes .cambio span')->text();
                }
                if($arr_acessorios) {
                    $arr[$i]['detalhes']['acessorios'] = $arr_acessorios;
                }
            }
            return $arr;

        } catch (\Exception $e) {
//            Log::error($e);
            return 'Ooops, não foi possível encontrar os dados solicitados';
        }
    }

    /**
     * Make web request to affiliate server url
     * @param String $url
     */
    public static function makeWebRequest($url, &$errors) {
        $client = new Client();

        try {
            $response = $client->get($url);
            if($response->getStatusCode() == 200) {
                return (string)$response->getBody();
            } else {
                $err = [
                    'code' => $response->getReasonPhrase(),
                ];
            }
        } catch (\Exception $e) {
            array_push($errors, 'Ooops, altere seu critério de busca e tente novamente');
            return;
        }


    }



    public static function parseItemDetailJson($result) {
        $response = '';
        $arr = [];
        $acessorios_lista = [];
        try {
            $crawler = new Crawler($result);

            if($crawler->filter('.veiculo-conteudo')->filter('.item-info')->count() > 0) {

                if($crawler->filter('.veiculo-conteudo')->filter('.item-info h1')->count() > 0) {
                    $cardDescription = $crawler->filter('.veiculo-conteudo')->filter('.item-info h1')->text();
                    if ($cardDescription) {
                        $arr['card_titulo'] = $cardDescription;
                    }
                }

                if($crawler->filter('.veiculo-conteudo')->filter('.item-info p')->count() >0 ) {
                    $desc = $crawler->filter('.veiculo-conteudo')->filter('.item-info p')->text();
                    if ($desc) {
                        $arr['descricao'] = $desc;
                    }
                }

                if($crawler->filter('.veiculo-conteudo')->filter('.item-info span ')->count() > 0) {
                    $price = $crawler->filter('.veiculo-conteudo')->filter('.item-info span ')->text();
                    if ($price) {
                        $arr['revenda'] = $price;
                    } else {
                        $arr['revenda'] = 'nao informado';
                    }
                }

                $detalhes_total = $crawler->filter('.veiculo-conteudo')->filter('.item-info dl dt')->count();
                for($i=0; $i<=$detalhes_total; $i++) {
                    if($crawler->filter('.veiculo-conteudo')->filter('.item-info dl dt')->eq($i)->count() > 0) {
                        $dt = $crawler->filter('.veiculo-conteudo')->filter('.item-info dl dt')->eq($i)->text();
                    }
                    if($crawler->filter('.veiculo-conteudo')->filter('.item-info dl dd')->eq($i)->count() > 0) {
                        $dd = $crawler->filter('.veiculo-conteudo')->filter('.item-info dl dd')->eq($i)->text();
                    }

                    if($dt && $dd) {
                        $arr['detalhes'][$dt] = $dd;
                    }
                }

                $acessorios =  $crawler->filter('.full-features')->filter('.list-styled')->count();
                if($acessorios > 0) {
                    for ($i = 0; $i <= $acessorios; $i++) {

                        $total_ul = $crawler->filter('.full-features')->filter('.list-styled')->eq($i)->filter('li')->count();
                        if ($total_ul > 0) {
                            for ($a = 0; $a <= $total_ul; $a++) {
                                if ($crawler->filter('.full-features')->filter('.list-styled')->eq($i)->filter('li')->eq($a)->count() > 0) {
                                    $acessorios_lista[] = $crawler->filter('.full-features')->filter('.list-styled')->eq($i)->filter('li')->eq($a)->text();
                                }
                            }
                        }
                    }
                    $arr['acessorios'] = $acessorios_lista;
                }

                if($crawler->filter('.full-content')->filter('p')->count() > 0) {
                    $observacao = $crawler->filter('.full-content')->filter('p')->text();
                    if($observacao) {
                        $arr['observacao'] = $observacao;
                    }
                }

                if($crawler->filter('.gallery-main')->filter('figure')->filter('img')->count() > 0) {
                    $img_main =  $crawler->filter('.gallery-main')->filter('figure')->filter('img')->attr('src');
                    if($img_main) {
                        $arr['img_url'] = $img_main;
                    }
                }

                if($crawler->filter('.gallery-main')->filter('.gallery-thumbs')->filter('li')->count() > 0) {
                    $total_img =  $crawler->filter('.gallery-main')->filter('.gallery-thumbs')->filter('li')->count();

                    for($s=0; $s<=$total_img; $s++) {
                        if($crawler->filter('.gallery-main')->filter('.gallery-thumbs')->filter('li')->eq($s)->filter('img')->count() > 0) {
                            $img = $crawler->filter('.gallery-main')->filter('.gallery-thumbs')->filter('li')->eq($s)->filter('img')->attr('src');
                            if($img) {
                                $arr['img_thumbs'][$s] = $img;
                            }
                        }
                    }
                    return $arr;
                }
            }
        } catch (\Exception $e) {
//            Log::error($e);
            return 'Ooops, não foi possível encontrar os dados solicitados';
        }
    }
}
