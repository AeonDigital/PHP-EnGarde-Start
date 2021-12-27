<?php
declare (strict_types=1);

namespace site\controllers;

use AeonDigital\EnGarde\MainController as MainController;
use AeonDigital\EnGarde\SessionControl\Enum\SecurityStatus as SecurityStatus;







/**
 * Controller
 */
class DomainUser extends MainController
{

    const defaultRouteConfig = [
        "description"       => "Home.",
        "allowedMimeTypes"  => ["html", "xhtml", "json"],
        "allowedMethods"    => ["GET"],
        "isUseXHTML"        => true
    ];





    public static $registerRoute_GET_login = [
        "description"       => "Login da aplicação.",
        "allowedMethods"    => ["GET", "POST"],
        "routes"            => ["/login"],
        "action"            => "executeLogin",
        "masterPage"        => "/masterPage.phtml",
        "view"              => "/home/login.phtml",
        "styleSheets"       => [
            "/css/main.css"
        ]
    ];
    public function executeLogin() {
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





    public static $registerRoute_GET_changeuserprofile = [
        "description"       => "Altera o perfil do UA logado.",
        "allowedMethods"    => ["POST"],
        "routes"            => ["/domainUser/ChangeUserProfile"],
        "action"            => "executeChangeUserProfile",
        "isSecure"          => false,
    ];
    public function executeChangeUserProfile() {
        $response = $this->retrieveDefaultResponse();

        if ($this->isAuthenticated() === false) {
            $response->message = "Esta ação não pode ser executada pois você não está autenticado.";
        }
        else {
            $fromURL = (string)$this->getPost("_fromURL");
            $userProfile = (string)$this->getPost("userProfile");
            if ($userProfile === "") {
                $response->message = "Você precisa informar o campo 'Perfil'.";
            }
            else {
                $r = $this->changeUserProfile($userProfile);
                if ($r === false) {
                    $response->message = "Não foi possível alterar seu perfil para o indicado [$userProfile].";
                }
                else {
                    $response->success = true;
                    $response->redirectTo = $this->retrieveUserProfile()["HomeURL"];

                    // Se foi informada a URL onde o usuário estava quando requisitou esta ação
                    if ($fromURL !== "") {
                        $router = new \AeonDigital\EnGarde\Engine\Router($this->serverConfig);
                        $rawRouteConfig = $router->selectTargetRawRoute(\strtok($fromURL, "?"));
                        if ($rawRouteConfig !== null && isset($rawRouteConfig["config"]["GET"]) === true)
                        {
                            $rawURLRoute = $rawRouteConfig["config"]["GET"]["activeRoute"];
                            $securitySession = $this->serverConfig->getSecuritySession();
                            if ($securitySession->checkRoutePermission("GET", $rawURLRoute) === true)
                            {
                                $response->redirectTo = $fromURL;
                            }
                        }
                    }

                    // Redireciona o usuário para a view alvo.
                    // Não há necessidade de mostrar qualquer resposta neste caso.
                    $this->serverConfig->redirectTo($response->redirectTo);
                }
            }
        }

        $this->viewData = $response;
    }
}
