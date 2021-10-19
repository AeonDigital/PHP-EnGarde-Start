<?php
declare (strict_types=1);

namespace site\controllers;

use AeonDigital\EnGarde\MainController as MainController;
use AeonDigital\EnGarde\SessionControl\Enum\SecurityStatus as SecurityStatus;







/**
 * Controller
 */
class Home extends MainController
{

    const defaultRouteConfig = [
        "description"       => "Home.",
        "allowedMimeTypes"  => ["html", "xhtml", "json"],
        "allowedMethods"    => ["GET"],
        "isUseXHTML"        => true
    ];





    public static $registerRoute_GET_index = [
        "description"       => "Página inicial.",
        "allowedMethods"    => ["GET"],
        "routes"            => ["/", "/home"],
        "action"            => "index",
        "masterPage"        => "/masterPage.phtml",
        "view"              => "/home/index.phtml",
        "styleSheets"       => [
            "/css/main.css"
        ],
        "javaScripts"       => [
            "/js/main.js"
        ],
        "responseDownloadFileName" => "nomeCustomizado.html"
    ];
    public function index() {
        $this->viewData->pageTitle = "EnGarde!";
        $this->viewData->viewTitle = "EnGarde! Framework";

        $ee = $this->getQueryParams();
    }
}
