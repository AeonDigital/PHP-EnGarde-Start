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
        "isUseXHTML"        => false
    ];





    public static $registerRoute_POST_form = [
        "description"       => "Permite editar ou salvar os dados para uma nova rota",
        "allowedMethods"    => ["POST", "PUT"],
        "routes"            => ["/flow/form"],
        "action"            => "flowForm"
    ];
    public function flowForm()
    {
        $formData = $this->retrieveFormFieldset("flowRoute_");
        if ($formData !== []) {
            $tgtController  = to_system_path(__DIR__ . "/FlowActions.php");
            $rawCode        = \file_get_contents($tgtController);
            $strCode        = $this->createRouteDefinition($formData);
            $actionExists   = false;


            // Identifica se a rota atualmente sendo definida já existe no escopo
            // do controller.
            require_once $tgtController;
            $controllerReflection = new \ReflectionClass(
                $this->serverConfig->getApplicationName() . "\\controllers\\FlowActions"
            );
            $staticProperties = $controllerReflection->getStaticProperties();
            if (\is_array($staticProperties) === true && \count($staticProperties) > 0) {
                foreach ($staticProperties as $propName => $value) {
                    if ($propName === $this->staticRegisterName) {
                        $actionExists = true;
                    }
                }
            }



            // Se está adicionando uma nova rota...
            if ($actionExists === false) {
                $strCode .= "\n\n\n\n\n    // @INSERT NEW ACTION HERE";
                $rawCode = \str_replace("// @INSERT NEW ACTION HERE", $strCode, $rawCode);
            }
            else {
                $useRegex = "/(\/\/ @ini " . $this->staticRegisterName . ")([\s\S]*)(\/\/ @end " . $this->staticRegisterName . ")/";
                $rawCode = \preg_replace($useRegex, $strCode, $rawCode);
            }


            \file_put_contents($tgtController, $rawCode);
            \redirect($formData["route"] . "?_method=DEV");
        }
    }




    private string $staticRegisterName = "";
    /**
     * Cria o código de uma rota com os dados indicados.
     *
     * @param       array $routeData
     *
     * @return      string
     */
    private function createRouteDefinition(array $routeData) : string
    {
        \extract($routeData);

        //
        // Formata o nome da rota para seu registro.
        $internalActionName = \str_replace("/", "_", trim($route, "\\/"));
        $internalActionName = \str_replace(
            $this->serverConfig->getApplicationName() . "_",
            "",
            $internalActionName
        );
        $this->staticRegisterName = "registerRoute_" . $method . "_" . $internalActionName;



        //
        // Corrige valor de devDescription
        $devDesc = ["\n            @indentbase"];
        $devDescription = \explode("\n", $devDescription);
        foreach ($devDescription as $line) {
            $devDesc[] = "            " . \str_replace("\"", "'", $line);
        }
        $devDescription = \implode("\n", $devDesc);


        //
        // Prepara as rotas relacionadas
        $flow = [];
        if (isset($selected) === true) {
            if (\count($selected) > 0) {
                foreach ($selected as $goingTo) {
                    $flow[] = "\"" . \str_replace(
                        "/" . $this->serverConfig->getApplicationName() . "/",
                        "/",
                        $goingTo
                    ) . "\"";
                }
            }
        }


        $str    = [];
        $str[]  = "// @ini " . $this->staticRegisterName;
        $str[]  = "    public static \$" . $this->staticRegisterName . " = [";
        $str[]  = "        \"description\" => \"$description\",";
        $str[]  = "        \"devDescription\" => \"$devDescription\",";
        $str[]  = "        \"allowedMethods\" => [\"$method\"],";
        $str[]  = "        \"routes\"         => [\"$route\"],";
        if (\count($flow) > 0) {
            $str[]  = "        \"relationedRoutes\"         => [";
            $str[]  = "            \"flow\" => [";
            $str[]  = "                \"default\" => [";
            $str[]  = "                    " . \implode(",\n                    ", $flow);
            $str[]  = "                ]";
            $str[]  = "            ]";
            $str[]  = "        ],";
        }
        $str[]  = "        \"action\"         => \"$internalActionName\"";
        $str[]  = "    ];";
        $str[]  = "    public function $internalActionName() { }";
        $str[]  = "    // @end " . $this->staticRegisterName;



        return \implode("\n", $str);
    }
}
