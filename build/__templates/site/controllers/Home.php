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
    }




    public static $registerRoute_GET_login = [
        "description"       => "Login da aplicação..",
        "allowedMethods"    => ["GET", "POST"],
        "routes"            => ["/login"],
        "action"            => "login",
        "masterPage"        => "/masterPage.phtml",
        "view"              => "/home/login.phtml",
        "styleSheets"       => [
            "/css/main.css"
        ]
    ];
    public function login() {
        $httpMethod = $this->serverConfig->getRequestMethod();
        if ($httpMethod === "GET") {
            $this->viewData->pageTitle = "EnGarde! Login";
            $this->viewData->viewTitle = "EnGarde! Login";
        }
        elseif ($httpMethod === "POST") {
            $securitySession = $this->serverConfig->getSecuritySession();
            $securityConfig = $this->serverConfig->getSecurityConfig();
            $formData = $this->retrieveFormFieldset("login_");

            $this->routeConfig->setMasterPage("");
            $this->routeConfig->setView("");

            $securitySession->executeLogin(
                $formData["userName"],
                sha1($formData["userPassword"]) // <-- Ver questão de política de encriptação de senha
            );
            // <-- ver como se dará a comunicação de formulários... se é mesmo tudo assincrono usando js.
            if ($securitySession->retrieveSecurityStatus() === SecurityStatus::UserSessionAuthenticated) {
                $this->serverConfig->redirectTo(
                    $securityConfig->getRouteToStart()
                );
            }
            else {
                $this->viewData->SecurityStatus = $securitySession->retrieveSecurityStatus();
            }
        }
    }
}
