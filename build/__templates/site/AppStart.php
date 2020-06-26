<?php
declare (strict_types=1);

namespace site;

use AeonDigital\EnGarde\MainApplication as MainApplication;








/**
 * Classe base da aplicação.
 */
class AppStart extends MainApplication
{



    /**
     * Configurações padrões para a aplicação.
     * Pode ser extendido na classe final da aplicação alvo.
     *
     * @var         array
     */
    protected array $defaultApplicationConfig = [
        "locales"                   => ["pt-BR", "en-US"],
        "defaultLocale"             => "pt-BR",
        "isUseLabels"               => true,
        "pathToErrorView"           => "/app-error.phtml",
        "pathToHttpMessageView"     => "/app-http.phtml",
        "httpSubSystemNamespaces"   => [
            "DEV" => "\\site\\subsystem\\http\\Flow"
        ],
        "defaultRouteConfig"        => [
            "isSecure"                  => true,
            "isAutoLog"                 => true
        ]
    ];
    /**
     * Configurações padrões para a aplicação.
     * Pode ser extendido na classe final da aplicação alvo.
     *
     * @var         array
     */
    protected array $defaultSecurityConfig = [
        "isActive"              => true,
        "dataCookieName"        => "cname",
        "securityCookieName"    => "sname",
        "routeToLogin"          => "/login",
        "routeToStart"          => "/",
        "routeToResetPassword"  => "/resetpassword",
        "anonymousId"           => 1,
        "sessionNamespace"      => "AeonDigital\\EnGarde\\SessionControl\\NativeDataBase",
        "isSessionRenew"        => true,
        "sessionTimeout"        => 40,
        "allowedFaultByIP"      => 50,
        "ipBlockTimeout"        => 50,
        "allowedFaultByLogin"   => 5,
        "loginBlockTimeout"     => 20,
        "allowedIPRanges"       => [],
        "deniedIPRanges"        => []
    ];
}
