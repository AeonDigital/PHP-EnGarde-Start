<?php
declare (strict_types=1);

namespace site\subsystem\http;

use AeonDigital\Interfaces\Http\Server\iResponseHandler as iResponseHandler;
use AeonDigital\Interfaces\Http\Message\iResponse as iResponse;
use AeonDigital\EnGarde\Interfaces\Config\iServer as iServerConfig;
use AeonDigital\EnGarde\Engine\Router as Router;





/**
 * Subsistema HTTP para responder ao método DEV.
 *
 * @package     AeonDigital\EnGarde
 * @author      Rianna Cantarelli <rianna@aeondigital.com.br>
 * @copyright   2020, Rianna Cantarelli
 * @license     ADPL-v1.0
 */
class Flow implements iResponseHandler
{





    /**
     * Instância de configuração do Servidor.
     *
     * @var         iServerConfig
     */
    private iServerConfig $serverConfig;
    /**
     * Objeto ``iResponse``.
     *
     * @var         iResponse
     */
    private iResponse $response;
    /**
     * Informação bruta sobre a rota que está sendo executada neste momento.
     *
     * @var         array
     */
    private array $rawRouteConfig = [];
    /**
     * Roteador para identificar as rotas relacionadas com a atual.
     *
     * @var         Router
     */
    private Router $router;





    /**
     * Dados da rota anterior a atual no fluxo de navegação.
     *
     * @var         array
     */
    private array $fromRouteData = [];
    /**
     * Dados da rota atual.
     *
     * @var         array
     */
    private array $atualRouteData = [];
    /**
     * Coleção de métodos HTTP válidos para a rota atual.
     *
     * @var         array
     */
    private array $atualRouteMethods = [];
    /**
     * Coleção de rotas que podem ser acessadas a partir desta.
     *
     * @var         array
     */
    private array $goingToRouteData = [];
    /**
     * Coleção de todas as rotas existentes na aplicação.
     * Apenas deve ser carregado em caso de estar em modo de formulário.
     *
     * @var         array
     */
    private array $collectionOfAllRoutesData = [];


    /**
     * Coleção de headers que devem ser enviados para o UA.
     *
     * @var         array
     */
    private array $useHeaders = [];










    /**
     * Inicia uma nova instância.
     *
     * @param       iServerConfig $serverConfig
     *              Instância ``iServerConfig``.
     *
     * @param       iResponse $response
     *              Instância ``iResponse``.
     */
    function __construct(
        iServerConfig $serverConfig,
        iResponse $response
    ) {
        $this->serverConfig     = $serverConfig;
        $this->response         = $response;
        $this->rawRouteConfig   = $this->serverConfig->getRawRouteConfig()["config"];
        $this->router           = new Router($this->serverConfig);


        // Resgata as informações básicas da rota atual.
        $this->fromRouteData    = $this->retrieveRouteDataFromParam("from");
        $this->atualRouteData   = $this->retrieveRouteDataFromParam("context");
        $useRawRouteConfig = [
            "application"               => $this->serverConfig->getApplicationName(),
            "namespace"                 => "\\site\\HTTPSubSystem",
            "controller"                => "\\site\\HTTPSubSystem\\Flow",
            "action"                    => "flow",
            "allowedMethods"            => ["GET", "POST", "PUT", "PATCH", "DELETE"],
            "allowedMimeTypes"          => ["html", "xhtml"],
            "method"                    => $this->atualRouteData["method"],
            "routes"                    => [$this->atualRouteData["route"]],
            "isUseXHTML"                => true,
            "runMethodName"             => "run",
            "responseIsPrettyPrint"     => true,
            "masterPage"                => "",
            "view"                      => "/subsystem/http/flow/index.phtml",
            "metaData"                  => [
                "EnGarde! Flow" => "Beta"
            ]
        ];
        $this->serverConfig->getRouteConfig($useRawRouteConfig, false);


        // Identifica todos os métodos válidos para esta rota.
        foreach ($this->rawRouteConfig as $method => $config) {
            $this->atualRouteMethods[] = $this->retrieveRouteData(
                $method,
                $this->serverConfig->getApplicationRequestUri()
            );

            // Resgata as informações das rotas relacionadas.
            if ($method === $this->atualRouteData["method"]) {
                $this->collectGoingToRoutesData($config);
            }
        }


        $formState = $this->serverConfig->getServerRequest()->getParam("form");
        if ($formState !== null) {
            $appRoutes = include $this->serverConfig->getApplicationConfig()->getPathToAppRoutes(true);
            foreach ($appRoutes as $type => $routes) {
                foreach ($routes as $route => $config) {
                    foreach ($config as $method => $cfg) {
                        $useRoute = str_replace("/^\/", "/", $route);
                        $useRoute = str_replace("\/", "/", $useRoute);
                        $useRoute = str_replace("\\", "", $useRoute);
                        $useRoute = str_replace("//", "", $useRoute);
                        $this->collectionOfAllRoutesData[] = $this->retrieveRouteData($method, $useRoute);
                    }
                }
            }
            \usort($this->collectionOfAllRoutesData, function($a, $b) {
                return strcmp($a["action"], $b["action"]);
            });

            if ($formState === "edit") {
                // Prepara os dados que irão preencher o formulário.
                $formData = \array_merge($this->atualRouteData, []);
                $formData["goingTo"] = [];

                unset($formData["rawRoute"]);
                foreach($this->goingToRouteData as $rConfig) {
                    unset($rConfig["rawRoute"]);
                    $formData["goingTo"][] = $rConfig;
                }
                $this->atualRouteData["formData"] = $formData;
            }
            else {
                $this->atualRouteData["formData"] = null;
            }
        }
    }









    /**
     * Prepara o objeto ``iResponse`` com os ``headers`` e com o ``body`` que deve ser usado
     * para responder ao ``UA``.
     *
     * @return      iResponse
     */
    public function prepareResponse() : iResponse
    {
        // Agrega informações no objeto viewData
        $viewData = (object)[
            "fromRouteData"             => $this->fromRouteData,
            "atualRouteData"            => $this->atualRouteData,
            "atualRouteMethods"         => $this->atualRouteMethods,
            "goingToRouteData"          => $this->goingToRouteData,
            "collectionOfAllRoutesData" => $this->collectionOfAllRoutesData
        ];
        $this->response = $this->response->withViewData($viewData);

        // Inicia o manipulador do mimetype alvo
        $mimeNS = "\\AeonDigital\\EnGarde\\Handler\\Mime\\XHTML";
        $mimeHandler = new $mimeNS(
            $this->serverConfig,
            $this->response
        );



        // Define o novo corpo para o objeto Response
        $useBody = $mimeHandler->createResponseBody();
        $body = $this->response->getBody();
        $body->write($useBody);
        $this->response = $this->response->withBody($body);


        // Prepara os Headers para o envio
        $this->prepareResponseHeaders(
            "application/xhtml+xml",
            "pt-BR",
            $this->response->getHeaders()
        );

        return $this->response;
    }





    /**
     * Retorna as informações básicas relacionadas a uma determinada rota.
     *
     * @param       string $method
     *              Método HTTP.
     *
     * @param       string $route
     *              Rota alvo.
     *
     * @return      array
     */
    private function retrieveRouteData(
        string $method,
        string $route
    ) : array {
        $method = \strtoupper($method);


        // Se o nome da aplicação está omitido na rota passada...
        $urlApplicationNamePart = "/" . $this->serverConfig->getApplicationName();
        if (\mb_str_starts_with($route, $urlApplicationNamePart) === false) {
            $route = $urlApplicationNamePart . \rtrim($route, "/\\");
        }

        $routeData   = [
            "rawRoute"      => null,
            "method"        => $method,
            "route"         => $route,
            "action"        => "$method $route",
            "link"          => "",
            "rawLinkData"   => "",
            "status"        => "default",
            "description"   => "",
            "devDescription"=> ""
        ];


        $routeData["rawRoute"] = $this->router->selectTargetRawRoute($route);
        if ($routeData["rawRoute"] !== null) {
            $routeData["rawRoute"] = $routeData["rawRoute"]["config"];

            if (\key_exists($method, $routeData["rawRoute"]) === false) {
                $routeData["rawRoute"] = null;
            }
            else {
                $routeData["description"] = $this->adjustRouteDescription(
                    $routeData["rawRoute"][$method]["description"]
                );
                $routeData["devDescription"] = $this->adjustRouteDevDescription(
                    $routeData["rawRoute"][$method]["devDescription"]
                );

                $jsonFData = \urlencode(
                    \json_encode([
                        "method" => $method,
                        "route" => $route,
                    ],
                    JSON_UNESCAPED_SLASHES)
                );
                $routeData["link"] = $route . "?_method=DEV&context=" . $jsonFData;
                $routeData["rawLinkData"] = $jsonFData;
            }
        }

        return $routeData;
    }
    /**
     * Retorna as informações básicas relacionadas a uma determinada rota
     * a partir de um parametro GET indicado.
     *
     * @param       string $param
     *              Nome do parametro GET.
     *
     * @return      array
     */
    public function retrieveRouteDataFromParam(string $param) : array
    {
        $r = [];
        $paramValue = $this->serverConfig->getServerRequest()->getParam($param);

        if ($paramValue === null) {
            if ($param === "context") {
                $r = $this->retrieveRouteData(
                    "GET",
                    $this->serverConfig->getApplicationRequestUri()
                );
            }
        }
        else {
            $err = "";
            $paramValue = \json_decode(\urldecode($paramValue), true);
            if (\key_exists("method", $paramValue) === false) {
                $err = "Invalid \"$param\" parameter. Lost key \"method\".";
            }
            elseif (\key_exists("route", $paramValue) === false) {
                $err = "Invalid \"$param\" parameter. Lost key \"route\".";
            }
            else {
                if ($param === "context") {
                    $paramValue["route"] = $this->serverConfig->getApplicationRequestUri();
                }

                $r = $this->retrieveRouteData(
                    $paramValue["method"],
                    $paramValue["route"]
                );


                if ($param === "context" && \key_exists("status", $paramValue) === true) {
                    $r["status"] = $paramValue["status"];
                }
            }

            if ($err !== "") {
                throw new \RuntimeException($err);
            }
        }

        return $r;
    }
    /**
     * Efetua um ajuste para preparar a descrição de uma rota para ser mostrada
     * na view XHTML.
     *
     * @param       string $description
     *              Descrição da rota.
     *
     * @return      string
     */
    private function adjustRouteDescription(string $description) : string
    {
        return \str_replace("\n", "<br />", $description);
    }
    /**
     * Efetua um ajuste para preparar a descrição de desenvolvimento de uma rota para
     * ser mostrada na view XHTML.
     *
     * @param       string $description
     *              Descrição da rota.
     *
     * @return      string
     */
    private function adjustRouteDevDescription(string $devDescription) : string
    {
        $useDevDescription  = \explode("\n", $devDescription);

        // Efetua um ajuste na indentação na descrição para desenvolvedores.
        $useIB = 0;
        $newDevDesc = [];
        foreach ($useDevDescription as $i => $line) {
            if (\trim($line) === "") {
                if (\count($newDevDesc) > 0) { $newDevDesc[] = ""; }
            }
            else {
                $line   = \rtrim($line);
                $ib     = \strpos($line, "@indentbase");
                if ($ib !== false && $useIB === 0) { $useIB = (int)$ib; }
                else { $newDevDesc[] = \substr($line, $useIB); }
            }
        }
        $useDevDescription = \implode("\n", $newDevDesc);

        if (\trim($useDevDescription) === "") {
            $useDevDescription = "Nenhuma informação técnica foi encontrada para esta rota.";
        }

        return $useDevDescription;
    }





    /**
     * Efetua a seleção das rotas que podem ser acessadas a partir da atual.
     *
     * @param       array $rawRouteConfig
     *              Configurações da rota principal já selecionada para o método
     *              em uso.
     *
     * @return      void
     */
    private function collectGoingToRoutesData(array $rawRouteConfig) : void
    {
        if (\key_exists("flow", $rawRouteConfig["relationedRoutes"]) === true) {
            $atualStatus        = $this->atualRouteData["status"];
            $flowRoutesRoutes   = $rawRouteConfig["relationedRoutes"]["flow"];

            if (\key_exists($atualStatus, $flowRoutesRoutes) === true) {

                $selectedRoutes = $flowRoutesRoutes[$atualStatus];
                if (\count($selectedRoutes) > 0) {
                    foreach ($selectedRoutes as $action) {
                        $splitAction = \explode(" ", $action);
                        if (\count($splitAction) === 2) {
                            $rConfig = $this->retrieveRouteData(
                                $splitAction[0],
                                $splitAction[1],
                            );

                            if ($rConfig["rawRoute"] === null) {
                                $rConfig["link"] .= "?_method=DEV&form=new";
                                $rConfig["link"] .= "&newmethod=" . $rConfig["method"];
                                $rConfig["link"] .= "&newroute=" . $rConfig["route"];
                            }
                            else {
                                $rConfig["link"] .= "&from=" . $this->atualRouteData["rawLinkData"];
                            }

                            $this->goingToRouteData[] = $rConfig;
                        }
                    }
                }
            }
        }
    }




    /**
     * Ajusta os headers do objeto Response antes do mesmo ser enviado ao ``UA``.
     *
     * @param       string $useMimeType
     *              Mimetype que deve ser usado.
     *
     * @param       string $useLocale
     *              Locale usado para a resposta.
     *
     * @param       array $useHeaders
     *              Coleção de headers a serem incorporados.
     *
     * @param       bool $isDownload
     *              Indica se é para realizar um download.
     *
     * @param       string $downloadFileName
     *              Nome do arquivo para download.
     *
     * @return      void
     */
    private function prepareResponseHeaders(
        string $useMimeType,
        string $useLocale,
        array $useHeaders
    ) : void {
        $now = new \DateTime();

        $http = "HTTP/" .
                $this->response->getProtocolVersion() . " " .
                $this->response->getStatusCode() . " " .
                $this->response->getReasonPhrase();

                // Prepara os headers que serão enviados.
        $this->useHeaders = [
            "$http"                 => "",
            "Framework"             => "EnGarde!; version=" . $this->serverConfig->getVersion(),
            "SubSystem"             => "EnGarde! Flow",
            "Application"           => $this->serverConfig->getApplicationConfig()->getAppName(),
            "Content-Type"          => $useMimeType . "; charset=utf-8",
            "Content-Language"      => $useLocale,
            "RequestDate"           => $this->serverConfig->getNow()->format("D, d M Y H:i:s"),
            "ResponseDate"          => $now->format("D, d M Y H:i:s")
        ];


        //
        // Nestes casos em especial não é indicado que seja feito o
        // cache dos resultado da requisição, portanto, incluirá
        // os headers que informam ao UA para que não o faça.
        //
        // Caso 1: O sistema de segurança está ativo.
        // Caso 2: O método HTTP sendo usado não é "cacheable"
        //         [Todos que NÃO forem GET e HEAD]
        if (($this->serverConfig->getSecurityConfig() !== null &&
            $this->serverConfig->getSecurityConfig()->getIsActive() === true) ||
            \in_array($this->serverConfig->getRequestMethod(), ["GET", "HEAD"]) === false)
        {
            $this->useHeaders = \array_merge(
                $this->useHeaders,
                [
                    "Expires"       => "Tue, 01 Jan 2000 00:00:00 UTC",
                    "Last-Modified" => $this->serverConfig->getNow()->format("D, d M Y H:i:s") . " UTC",
                    "Cache-Control" => "no-store, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0",
                    "Pragma"        => "no-cache"
                ],
            );
        }



        // Adiciona os headers definidos na action mas não substitui
        // os aqui definidos.
        foreach ($useHeaders as $key => $value) {
            if (isset($this->useHeaders[$key]) === false) {
                $this->useHeaders[$key] = \implode(", ", $value);
            }
        }


        // Aplica os headers no objeto response.
        $this->response = $this->response->withHeaders($this->useHeaders, false);
    }
}
