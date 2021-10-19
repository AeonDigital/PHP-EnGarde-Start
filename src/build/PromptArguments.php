<?php
declare (strict_types=1);

namespace AeonDigital\EnGarde\Build;




/**
 * Classe validadora de argumentos.
 */
class PromptArguments
{
    use \AeonDigital\Traits\MainCheckArgumentException;



    /**
     * Efetua a leitura dos argumentos configurados para um determinado comando e
     * retorna um objeto stdClass preenchido com todos os itens.
     * Itens omitidos ou vazios serão retornados como ``''``.
     *
     * @param       array $argumentRules
     *              Coleção de argumentos que devem ser retornados.
     *
     * @return      \stdClass
     */
    public function readArguments(array $argumentRules) : \stdClass
    {
        $args = new \stdClass();


        $confirm = true;
        foreach ($argumentRules as $argRules) {
            if ($confirm === true) {
                $argRules       = $this->checkBasicRules($argRules);
                $argName        = $argRules["argName"];

                if ($argName === "confirmCommand") {
                    $confirm = $this->confirmCommand($argRules);
                    if ($confirm === false) {
                        echo $argRules["onAbort"];
                        exit();
                    }
                }
                else {
                    $validateRules      = $argRules["validateRules"];
                    $rawArg             = $this->readRawValueAndParse($argRules);
                    $args->{$argName}   = $this->mainCheckForInvalidArgumentException(
                        $argName,
                        $rawArg,
                        $validateRules
                    );
                }
            }
        }

        return $args;
    }




    /**
     * Efetua a validação da configuração principal sobre o argumento que
     * deve ser obtido do usuário.
     *
     * @param       array $argRules
     *              Array de regras de validação para um argumento.
     *
     * @return      array
     */
    private function checkBasicRules(array $argRules) : array
    {
        $argName        = (string)$argRules["argName"];
        $question       = implode("\n", $argRules["question"]);
        $type           = (string)$argRules["type"];


        $err        = "";
        $raw        = null;
        $rawParsed  = null;
        if ($argName === "") {
            $err = "Development error: 'argName' must be defined";
        }
        else {
            if ($question === "") {
                $err = "Development error: 'question' must be defined";
            }
            else {
                if ($type === "") {
                    $err = "Development error: 'type' must be defined";
                }
                else {
                    $allowedType = [
                        "bool", "int", "float", "string", "date", "time", "datetime"
                    ];

                    if (\in_array($type, $allowedType) === false) {
                        $err .= "Development error: invalid value defined for 'type'.\n";
                        $err .= "Given      : [ $type ]\n";
                        $err .= "Expected   : [ " . \implode(", ", $allowedType) . " ] \n";
                    }
                }


                if ($argName === "confirmCommand") {
                    $accept = $argRules["accept"];
                    $deny   = $argRules["deny"];

                    if (\is_array($accept) === false || \count($accept) === 0) {
                        $err = "Development error: 'accept' must be an non empty array";
                    }
                    else {
                        if (\is_array($deny) === false || \count($deny) === 0) {
                            $err = "Development error: 'deny' must be an non empty array";
                        }
                    }

                    if (\key_exists("onAbort", $argRules) === false) {
                        $argRules["onAbort"] = "Aborted by user.\n\n";
                    }
                    else {
                        $argRules["onAbort"] = implode("\n", $argRules["onAbort"]);
                    }


                    $question = "\n\n----- ----- ----- ----- ----- ----- \n" . $question;
                    $question .= "\n To execute (" . implode(", ", $accept) . ");";
                    $question .= "\n To cancel (" . implode(", ", $deny) . ");";
                    $question .= "\n → ";
                }
                else {
                    $question .= "\n ($type) → ";
                }
            }
        }


        if ($err !== "") {
            throw new \RuntimeException($err);
        }
        else {
            $argRules["question"] = $question;
            return $argRules;
        }
    }



    /**
     * Efetua a leitura do valor que deve ser obtido e converte-o no tipo
     * especificado pelas regras.
     *
     * @param       array $argRules
     *              Array de regras de validação para um argumento.
     *
     * @return      void
     */
    private function readRawValueAndParse(array $argRules)
    {
        \extract($argRules);


        $err = "";
        echo $question;
        $raw = \readline("");
        $rawParsed = null;
        echo "\n";


        switch ($type) {
            case "bool":
                $rawParsed = \AeonDigital\Tools::toBool($raw);
                break;
            case "int":
                $rawParsed = \AeonDigital\Tools::toInt($raw);
                break;
            case "float":
                $rawParsed = \AeonDigital\Tools::toFloat($raw);
                break;
            case "string":
                $rawParsed = \AeonDigital\Tools::toString($raw);
                break;
            case "date":
            case "time":
            case "datetime":
                if ($type === "date") { $rawParsed = $raw . " 00:00:00"; }
                elseif ($type === "time") { $rawParsed = "2000-01-01 " . $raw; }

                $rawParsed = \AeonDigital\Tools::toDateTime($rawParsed);
                break;
        }


        if ($rawParsed === null) {
            $err .= "Invalid value defined for $argName.\n";
            $err .= "Given      : $raw \n";
            $err .= "Expected   : $type or compatible value \n";
        }


        if ($err !== "") {
            throw new \RuntimeException($err);
        }
        else {
            return $rawParsed;
        }
    }



    /**
     * Verifica se o comando deve mesmo ser executado.
     *
     * @param       array $argRules
     *              Array de regras de validação para um argumento.
     *
     * @return      bool
     */
    private function confirmCommand(array $argRules) : bool
    {
        echo $argRules["question"];
        $raw = \readline("");
        echo "\n";

        return (\array_in_ci($raw, $argRules["accept"]) === true);
    }

}
