<?php
declare (strict_types=1);

namespace site\controllers;

use AeonDigital\EnGarde\MainController as MainController;








/**
 * Controller
 */
class Documentation extends MainController
{

    const defaultRouteConfig = [
        "description"       => "Home.",
        "allowedMimeTypes"  => ["html", "xhtml", "json"],
        "allowedMethods"    => ["GET"],
        "isUseXHTML"        => true,
        "masterPage"        => "/masterPage.phtml",
        "styleSheets"       => [
            "/css/main.css"
        ],
        "javaScripts"       => [
            "/js/main.js"
        ]
    ];








    public static $registerRoute_GET_about = [
        "description"       => "Informações sobre o Framework EnGarde!.",
        "allowedMethods"    => ["GET"],
        "routes"            => ["/documentation/about"],
        "action"            => "about",
        "view"              => "/documentation/about.phtml"
    ];
    public function about() {
        $this->viewData->pageTitle = "EnGarde! | Sobre";
        $this->viewData->viewTitle = "Sobre";
    }





    public static $registerRoute_GET_structure = [
        "description"       => "Informações sobre o Framework EnGarde!.",
        "allowedMethods"    => ["GET"],
        "routes"            => ["/documentation/structure"],
        "action"            => "structure",
        "view"              => "/documentation/structure.phtml"
    ];
    public function structure() {
        $this->viewData->pageTitle = "EnGarde! | Estrutura de Diretórios";
        $this->viewData->viewTitle = "Estrutura de Diretórios";
    }





    public static $registerRoute_GET_domain_config = [
        "description"       => "Informações sobre o Framework EnGarde!.",
        "allowedMethods"    => ["GET"],
        "routes"            => ["/documentation/domain_config"],
        "action"            => "domainConfig",
        "view"              => "/documentation/domain_config.phtml"
    ];
    public function domainConfig() {
        $this->viewData->pageTitle = "EnGarde! | Configuração do Domínio";
        $this->viewData->viewTitle = "Configuração do Domínio";
    }





    public static $registerRoute_GET_application = [
        "description"       => "Informações sobre o Framework EnGarde!.",
        "allowedMethods"    => ["GET"],
        "routes"            => ["/documentation/application"],
        "action"            => "application",
        "view"              => "/documentation/application.phtml"
    ];
    public function application() {
        $this->viewData->pageTitle = "EnGarde! | Application";
        $this->viewData->viewTitle = "Application";
    }





    public static $registerRoute_GET_controller = [
        "description"       => "Informações sobre o Framework EnGarde!.",
        "allowedMethods"    => ["GET"],
        "routes"            => ["/documentation/controller"],
        "action"            => "controller",
        "view"              => "/documentation/controller.phtml"
    ];
    public function controller() {
        $this->viewData->pageTitle = "EnGarde! | Controller";
        $this->viewData->viewTitle = "Controller";
    }





    public static $registerRoute_GET_route = [
        "description"       => "Informações sobre o Framework EnGarde!.",
        "allowedMethods"    => ["GET"],
        "routes"            => ["/documentation/route"],
        "action"            => "route",
        "view"              => "/documentation/route.phtml"
    ];
    public function route() {
        $this->viewData->pageTitle = "EnGarde! | Route";
        $this->viewData->viewTitle = "Route";
    }





    public static $registerRoute_GET_action = [
        "description"       => "Informações sobre o Framework EnGarde!.",
        "allowedMethods"    => ["GET"],
        "routes"            => ["/documentation/action"],
        "action"            => "action",
        "view"              => "/documentation/action.phtml"
    ];
    public function action() {
        $this->viewData->pageTitle = "EnGarde! | Action";
        $this->viewData->viewTitle = "Action";
    }





    public static $registerRoute_GET_action_serverconfig = [
        "description"       => "Informações sobre o Framework EnGarde!.",
        "allowedMethods"    => ["GET"],
        "routes"            => ["/documentation/action_serverconfig"],
        "action"            => "action_serverconfig",
        "view"              => "/documentation/action_serverconfig.phtml"
    ];
    public function action_serverconfig() {
        $this->viewData->pageTitle = "EnGarde! | Action - Server Config";
        $this->viewData->viewTitle = "Action - Server Config";
    }





    public static $registerRoute_GET_action_serverrequest = [
        "description"       => "Informações sobre o Framework EnGarde!.",
        "allowedMethods"    => ["GET"],
        "routes"            => ["/documentation/action_serverrequest"],
        "action"            => "action_serverrequest",
        "view"              => "/documentation/action_serverrequest.phtml"
    ];
    public function action_serverrequest() {
        $this->viewData->pageTitle = "EnGarde! | Action - Server Request";
        $this->viewData->viewTitle = "Action - Server Request";
    }





    public static $registerRoute_GET_action_applicationconfig = [
        "description"       => "Informações sobre o Framework EnGarde!.",
        "allowedMethods"    => ["GET"],
        "routes"            => ["/documentation/action_applicationconfig"],
        "action"            => "action_applicationconfig",
        "view"              => "/documentation/action_applicationconfig.phtml"
    ];
    public function action_applicationconfig() {
        $this->viewData->pageTitle = "EnGarde! | Action - Application Config";
        $this->viewData->viewTitle = "Action - Application Config";
    }





    public static $registerRoute_GET_action_routeconfig = [
        "description"       => "Informações sobre o Framework EnGarde!.",
        "allowedMethods"    => ["GET"],
        "routes"            => ["/documentation/action_routeconfig"],
        "action"            => "action_routeconfig",
        "view"              => "/documentation/action_routeconfig.phtml"
    ];
    public function action_routeconfig() {
        $this->viewData->pageTitle = "EnGarde! | Action - Route Config";
        $this->viewData->viewTitle = "Action - Route Config";
    }





    public static $registerRoute_GET_action_viewdata = [
        "description"       => "Informações sobre o Framework EnGarde!.",
        "allowedMethods"    => ["GET"],
        "routes"            => ["/documentation/action_viewdata"],
        "action"            => "action_viewdata",
        "view"              => "/documentation/action_viewdata.phtml"
    ];
    public function action_viewdata() {
        $this->viewData->pageTitle = "EnGarde! | Action - View Data";
        $this->viewData->viewTitle = "Action - View Data";
    }





    public static $registerRoute_GET_action_viewconfig = [
        "description"       => "Informações sobre o Framework EnGarde!.",
        "allowedMethods"    => ["GET"],
        "routes"            => ["/documentation/action_viewconfig"],
        "action"            => "action_viewconfig",
        "view"              => "/documentation/action_viewconfig.phtml"
    ];
    public function action_viewconfig() {
        $this->viewData->pageTitle = "EnGarde! | Action - View Config";
        $this->viewData->viewTitle = "Action - View Config";
    }




}
