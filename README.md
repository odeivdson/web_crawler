# Crawler | web car 

Busca de veículos seminovos BH.


### Pre-requisitos


```
servidor web, php
```

### Setup

Siga os passos abaixo para clonar e inicializar o projeto.
```
$ git clone https://github.com/odeivdson/web_crawler.git
$ cd web_crawler
$ composer install
```

Inicialize o servidor local

```
php artisan serve

---
Você deve ter uma resposta como:
Laravel development server started: http://127.0.0.1:8000
[Sun May 31 10:36:17 2020] PHP 7.4.3 Development Server (http://127.0.0.1:8000) started
```

### Documentação básica

Rotas API:


Listar todos os veículos (informações gerais) com base em filtros
- GET - /api/lista

Parâmetros 
```
'page' => 'deve ser um inteiro com no máximo 3 dígitos',
'tipo_veiculo' => 'deve ser: carro, moto ou caminhao',
'marca_veiculo' => 'deve ser do tipo string',
'modelo_veiculo' => 'deve ser do tipo string',
'ano_veiculo_min' => 'deve ser um inteiro de 4 dígitos',
'ano_veiculo_max' => 'deve ser um inteiro de 4 dígitos',
'preco_veiculo_min' => 'deve ser um inteiro de no mínimo 4 e máximo 8 dígitos',
'preco_veiculo_max' => 'deve ser um inteiro de no mínimo 4 e máximo 8 dígitos',
```

Exemplo de consulta:

```
{
    "content": {
        "page_info": "Exibindo a página 1 de um total de 19 paginas.",
        "0": {
            "img_url": "https://tcarros.seminovosbh.com.br/mini_ford/ecosport/2011/2012/2677609/63351a1ee7a94ad8fa4562f56d275f83349c",
            "id_detalhe": "ford-ecosport-freestyle-1.6-8v-flex-5p-1.6-8v-5portas-2011-2012--2677609",
            "descricao": "1.6 8v FREESTYLE 1.6 8V Flex 5p",
            "valor_evenda": "R$ 26.900,00",
            "detalhes": {
                "ano": "2011/2012",
                "kilometragem": "137.000km",
                "combustivel": "Bi-Combustível",
                "cambio": "Manual",
                "acessorios": "•ALARME •AR CONDICIONADO •CÂMBIO MANUAL •CD / MP3 •CENTRAL MULTIMIDIA •COMPUTADOR DE BORDO •DIREÇÃO HIDRÁULICA •LIMPADOR TRASEIRO"
            }
        },
        "1": {
            "img_url": "https://tcarros.seminovosbh.com.br/mini_ford/ecosport/2010/2011/2737277/65506367d4dbda63b44b2fff944f36c876f6",
            "id_detalhe": "ford-ecosport-xlt-freestyle-1.6-flex-8v-5p-1.6-8v-5portas-2010-2011--2737277",
            "descricao": "XLT FREESTYLE 1.6 Flex 8V 5p",
            "valor_evenda": "R$ 29.900,00",
            "detalhes": {
                "ano": "2010/2011",
                "kilometragem": "100.100km",
                "combustivel": "Bi-Combustível",
                "cambio": "Manual",
                "acessorios": "•ALARME •AR CONDICIONADO •AR QUENTE •BANCO AJUSTE ALTURA •CÂMBIO MANUAL •CD / MP3 •COMPUTADOR DE BORDO •DESEMBAÇADOR"
            }
        },
        ...
    },
    "Status": "success",
    "Message": "Registros localizados com sucesso"
}
```


-----

Obter detalhes específicos (de um veículo encontrado na busca logo acima)
- GET - /api/detalhe

Parâmetros
```
'id_detalhe' => 'obrigatório, deve ser obtido na busca realizada na rota /api/lista'
```

Exemplo de consulta:

```
{
    "content": {
        "card_titulo": "Ford EcoSport",
        "descricao": "1.6 8v FREESTYLE 1.6 8V Flex 5p",
        "revenda": "R$ 26.900,00",
        "detalhes": {
            "Ano/Modelo": "2011/2012",
            "Quilometragem": "137.000 Km",
            "Câmbio": "Manual",
            "Portas": "5",
            "Combustível": "Bi-Combustível",
            "Cor": "Preto",
            "Placa": "HLK",
            "Troca?": "Aceito Troca"
        },
        "acessorios": [
            "ALARME",
            "AR CONDICIONADO",
            "CÂMBIO MANUAL",
            "CD / MP3",
            "CENTRAL MULTIMIDIA",
            "COMPUTADOR DE BORDO",
            "DIREÇÃO HIDRÁULICA",
            "LIMPADOR TRASEIRO",
            "MP3 / USB",
            "RETROVISORES ELÉTRICOS",
            "RODAS DE LIGA LEVE",
            "TRAVAS ELÉTRICAS",
            "VIDROS ELÉTRICOS",
            "VOLANTE AJUSTÁVEL"
        ],
        "observacao": "R$26.800,00 para venda (TROCAS, considerar FIPE R$ 33mil). Carro conservado, possui manual e chave reserva. Manutenção ok. Completa, Ar Condicionado, Direção hidráulica, rodas, vidro, trava e retrovisores elétricos, som MP3/UBS, alarme, etc. Mais informações, Whatsapp: (31)98741-7584",
        "img_url": "https://carros.seminovosbh.com.br/ford/ecosport/2011/2012/2677609/63351a1ee7a94ad8fa4562f56d275f83349c",
        "img_thumbs": [
            "https://tcarros.seminovosbh.com.br/mini_ford/ecosport/2011/2012/2677609/63351a1ee7a94ad8fa4562f56d275f83349c",
            "https://tcarros.seminovosbh.com.br/mini_ford/ecosport/2011/2012/2677609/4944386d1ac80ab18db643a0829684aaf6a1",
            "https://tcarros.seminovosbh.com.br/mini_ford/ecosport/2011/2012/2677609/4944104d08595c12227027fcb932714f7910",
            "https://tcarros.seminovosbh.com.br/mini_ford/ecosport/2011/2012/2677609/8494f2a0e7dd4a3bc3f55a8d445b2faf3653",
            "https://tcarros.seminovosbh.com.br/mini_ford/ecosport/2011/2012/2677609/84945189b928278d308e4e6869a0da3d47e5",
            "https://tcarros.seminovosbh.com.br/mini_ford/ecosport/2011/2012/2677609/70617cc0e4763f0545a0d2d808d8a89c8a60",
            "https://tcarros.seminovosbh.com.br/mini_ford/ecosport/2011/2012/2677609/706168c7f4dbcf2d2f646825e4063cef8f0f",
            "https://seminovos.com.br/img/sample/sample-thumb.jpg",
            "https://seminovos.com.br/img/sample/sample-thumb.jpg",
            "https://seminovos.com.br/img/sample/sample-thumb.jpg",
            "https://seminovos.com.br/img/sample/sample-thumb.jpg",
            "https://seminovos.com.br/img/sample/sample-thumb.jpg"
        ]
    },
    "Status": "success",
    "Message": "Registros localizados com sucesso"
}
```

### Testes via postman

Após inicializar o ambiente com sucesso (http://127.0.0.1:8000), você pode utilizar o Postman importando o arquivo 'Web Crawler.postman_collection.json' que está na raiz do projeto ;)