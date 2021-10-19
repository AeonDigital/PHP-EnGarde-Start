<?php
declare (strict_types=1);

namespace site\controllers;

use AeonDigital\EnGarde\MainController as MainController;








/**
 * Controller
 */
class FlowActions extends MainController
{

    const defaultRouteConfig = [
        "description"       => "EnGarde! Flow",
        "allowedMimeTypes"  => ["html"],
    ];





    // @ini registerRoute_GET_flow
    public static $registerRoute_GET_flow = [
        "description" => "EnGarde! Flow",
        "devDescription" => "
            @indentbase
            Inicie por aqui seu fluxo de rotas HTTP.

            Adicione novas e/ou edite as existentes usando os controles e formulários disponíveis.",
        "allowedMethods" => ["GET"],
        "routes"         => ["/flow"],
        "relationedRoutes"         => [
            "flow" => [
                "default" => [
                    "GET /produto/lista"
                ]
            ]
        ],
        "action"         => "flow"
    ];
    public function flow() { }
    // @end registerRoute_GET_flow





    // @ini registerRoute_GET_documentation_aboute
    public static $registerRoute_GET_documentation_aboute = [
        "description" => "Teste",
        "devDescription" => "
            @indentbase
            teste",
        "allowedMethods" => ["GET"],
        "routes"         => ["/documentation/aboute"],
        "relationedRoutes"         => [
            "flow" => [
                "default" => [
                    "GET /site",
                    "GET /documentation/about",
                    "GET /documentation/action"
                ]
            ]
        ],
        "action"         => "documentation_aboute"
    ];
    public function documentation_aboute() { }
    // @end registerRoute_GET_documentation_aboute





    // @ini registerRoute_GET_produto_lista
    public static $registerRoute_GET_produto_lista = [
        "description" => "Lista de produtos",
        "devDescription" => "
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
                - Selo        [ checkbox ]",
        "allowedMethods" => ["GET"],
        "routes"         => ["/produto/lista"],
        "relationedRoutes"         => [
            "flow" => [
                "default" => [
                    "GET /produto/detalhe"
                ]
            ]
        ],
        "action"         => "produto_lista"
    ];
    public function produto_lista() { }
    // @end registerRoute_GET_produto_lista




    // @ini registerRoute_GET_produto_detalhe
    public static $registerRoute_GET_produto_detalhe = [
        "description" => "Detalhe de produtos",
        "devDescription" => "
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
        "allowedMethods" => ["GET"],
        "routes"         => ["/produto/detalhe"],
        "relationedRoutes"         => [
            "flow" => [
                "default" => [
                    "GET /produto/lista",
                    "GET /produto/carrinho"
                ]
            ]
        ],
        "action"         => "produto_detalhe"
    ];
    public function produto_detalhe() { }
    // @end registerRoute_GET_produto_detalhe




    // @ini registerRoute_GET_produto_carrinho
    public static $registerRoute_GET_produto_carrinho = [
        "description" => "Mostra os itens no carrinho de compras",
        "devDescription" => "
            @indentbase
            Aqui deve ser mostrada uma tabela contendo os itens que o usuário selecionou.
            Na tabela deve conter:
                - Produto
                - Quantidade [ e uma forma de que o usuário possa alterá-la ]
                - Valor unitário
                - Valor total [ valor em função da quantidade de itens ]
                - Excluir [ botão que permita remover o item do carrinho ]
            ",
        "allowedMethods" => ["GET"],
        "routes"         => ["/produto/carrinho"],
        "relationedRoutes"         => [
            "flow" => [
                "default" => [
                    "GET /produto/lista",
                    "GET /produto/checkout"
                ]
            ]
        ],
        "action"         => "produto_carrinho"
    ];
    public function produto_carrinho() { }
    // @end registerRoute_GET_produto_carrinho




    // @ini registerRoute_GET_produto_checkout
    public static $registerRoute_GET_produto_checkout = [
        "description" => "Apresenta para o usuário o formulário de pagamento",
        "devDescription" => "
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
        "allowedMethods" => ["GET"],
        "routes"         => ["/produto/checkout"],
        "relationedRoutes"         => [
            "flow" => [
                "default" => [
                    "GET /produto/lista",
                    "GET /flow"
                ]
            ]
        ],
        "action"         => "produto_checkout"
    ];
    public function produto_checkout() { }
    // @end registerRoute_GET_produto_checkout




    // @INSERT NEW ACTION HERE
}
