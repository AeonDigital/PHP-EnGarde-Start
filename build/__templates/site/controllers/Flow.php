<?php
declare (strict_types=1);

namespace site\controllers;

use AeonDigital\EnGarde\MainController as MainController;








/**
 * Controller
 */
class Flow extends MainController
{

    const defaultRouteConfig = [
        "description"       => "EnGarde! Flow",
        "allowedMimeTypes"  => ["html", "xhtml", "json"],
        "allowedMethods"    => ["GET"],
        "isUseXHTML"        => true
    ];





    public static $registerRoute_GET_index = [
        "description"       => "Início do fluxo de vendas.",
        "devDescription"    => "
            @indentbase

            O seguinte texto é para ser formatado corretamente no sub-sistema ``Flow``.
            Deve ser adequadamente alinhado para permitir uma leitura agradável.

              - Isto é uma lista
              - isto é outra lista.

            Este software está licenciado sob a [Licença ADPL-v1.0](LICENSE).
        ",
        "allowedMethods"    => ["GET"],
        "routes"            => ["/flow"],
        "relationedRoutes"  => [
            "flow" => [
                "default" => [
                    "GET /flow/produto/lista",
                    //"GET /flow/produto/lista filtrada"
                ],
            ]
        ],
        "action"            => "index"
    ];
    public function index() { }





    public static $registerRoute_GET_produto_lista = [
        "description"       => "Lista de produtos",
        "devDescription"    => "
            @indentbase

            Nesta tela devem estar presentes todos os produtos disponíveis para a compra.
            Cada produto deve ser mostrado com os seguintes dados:
              - Imagem
              - Nome
              - Categoria
              - Preço
              - Selo de Promoção

            O usuário precisa ter acesso a um filtro para seleção dos produtos.
            O filtro deve funcionar seguindo os seguintes critérios:
              - Nome        ['case insensitive' e 'ignorar acentuação e glifos']
              - Categoria   [ select com os itens disponíveis ]
              - Preço       [ 2 caixas de texto -> DE; ATÉ ]
              - Selo        [ checkbox ]
        ",
        "allowedMethods"    => ["GET"],
        "routes"            => ["/flow/produto/lista"],
        "relationedRoutes"  => [
            "flow" => [
                "default" => [
                    //"GET /flow/produto/lista filtrada",
                    "GET /flow/produto/detalhe"
                ],
                "filtrada" => [
                    "GET /flow/produto/lista",
                    "GET /flow/produto/detalhe"
                ]
            ]
        ],
        "action"            => "produto_lista"
    ];
    public function produto_lista() { }





    public static $registerRoute_GET_produto_detalhe = [
        "description"       => "Detalhe de produtos",
        "devDescription"    => "
            @indentbase

            Nesta tela deve ser apresentados todos os dados do produto escolhido para que o
            usuário possa ter acesso as informações pertinentes.

            As seguintes informações devem estar disponíveis:
              - Imagem [ carrocel de imagens]
              - Nome
              - Categoria
              - Preço
              - Selo de Promoção
              - Características do produto
              - Informações sobre garantia
              - Informações sobre entrega (preço, prazo...)
              - Avaliações e Mensagens de outros compradores.
        ",
        "allowedMethods"    => ["GET"],
        "routes"            => ["/flow/produto/detalhe"],
        "relationedRoutes"  => [
            "flow" => [
                "default" => [
                    "GET /flow/produto/lista",
                    "GET /flow/carrinho",
                ],
                "carrinho" => [
                    "GET /flow/carrinho"
                ]
            ]
        ],
        "action"            => "produto_detalhe"
    ];
    public function produto_detalhe() { }





    public static $registerRoute_GET_carrinho = [
        "description"       => "Mostra os itens no carrinho de compras",
        "devDescription"    => "
            @indentbase

            Aqui deve ser mostrada uma tabela contendo os itens que o usuário selecionou.
            Na tabela deve conter:
            - Produto
            - Quantidade [ e uma forma de que o usuário possa alterá-la ]
            - Valor unitário
            - Valor total [ valor em função da quantidade de itens ]
            - Excluir [ botão que permita remover o item do carrinho ]
        ",
        "allowedMethods"    => ["GET"],
        "routes"            => ["/flow/carrinho"],
        "relationedRoutes"  => [
            "flow" => [
                "default" => [
                    "GET /flow/produto/lista",
                    "GET /flow/checkout",
                ],
                "carrinho" => [
                    "GET /flow/carrinho"
                ]
            ]
        ],
        "action"            => "carrinho"
    ];
    public function carrinho() { }





    public static $registerRoute_GET_checkout = [
        "description"       => "Apresenta para o usuário o formulário de pagamento",
        "devDescription"    => "
            @indentbase

            Nesta tela devem ser apresentadas ao usuário as formas de pagamento.
              - Boleto
              - Cartão de Crédito
              - Débito em Conta

            Ao finalizar o pagamento, uma mensagem de sucesso deve ser mostrada e
            o usuário deve ter acesso a um único botão indicando 'Voltar para a home'

            Em caso de erro ao realizar o procedimento deve ser mostrada para o
            usuário uma mensagem indicando o motivo e permitindo que ele possa, se quiser,
            realizar uma nova tentativa.
        ",
        "allowedMethods"    => ["GET"],
        "routes"            => ["/flow/checkout"],
        "relationedRoutes"  => [
            "flow" => [
                "default" => [
                    "GET /flow",
                    "GET /flow/produto/lista",
                ]
            ]
        ],
        "action"            => "checkout"
    ];
    public function checkout() { }
}
