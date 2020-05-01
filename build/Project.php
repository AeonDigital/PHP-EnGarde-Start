<?php
declare (strict_types=1);

namespace AeonDigital\EnGarde\Build;

require_once dirname(__DIR__) . "\\vendor\\autoload.php";



class Project
{





    private static array $start_arguments_01 = [
        [
            "argName"       => "confirmCommand",
            "question"      => [
                ":: ATENÇÃO",
                ":: ESTA AÇÃO NÃO PODE SER DESFEITA.",
                "::",
                "::   Se o template do projeto selecionado possuir o mesmo nome de uma outra aplicação ",
                "::   a versão atual será substituida pela nova.",
                "::",
                ":: Você tem certeza que deseja prosseguir?"
            ],
            "type"      => "bool",
            "accept"    => ["sim", "yes", "y", "s", "1"],
            "deny"      => ["nao", "no", "n", "0"],
            "onAbort"  => [
                ":: Ação cancelada pelo usuário.\n\n"
            ]
        ],
        [
            "argName"       => "environment",
            "question"      => [
                ":: Defina o tipo de ambiente em que esta instalação está sendo feita",
                ":: Os seguintes valores são válidos: [\"DEV\", \"LCL\", \"PRD\"]"
            ],
            "type"          => "string",
            "validateRules" => [
                ["validate" => "is string"],
                [
                    "validate"          => "is allowed value",
                    "allowedValues"     => ["DEV", "LCL", "PRD"],
                    "caseInsensitive"   => true
                ]
            ]
        ],
        [
            "argName"       => "projectTemplate",
            "question"      => [
                ":: Selecione o template do projeto que deseja efetuar a instalação",
                ":: Os seguintes valores são válidos: [\"site\"]"
            ],
            "type"          => "string",
            "validateRules" => [
                ["validate" => "is string"],
                [
                    "validate"          => "is allowed value",
                    "allowedValues"     => ["site"],
                    "caseInsensitive"   => true
                ]
            ]
        ]
    ];
    /**
     * Inicia um novo projeto
     *
     * @return      void
     */
    public static function start() : void
    {
        $prompt             = new PromptArguments();
        $args               = $prompt->readArguments(self::$start_arguments_01);

        $env                = \strtolower($args->environment);
        $projectTemplate    = $args->projectTemplate;


        $rootPath               = dirname(__DIR__);
        $pathToTemplate         = \to_system_path($rootPath . "/build/__templates/");
        $pathToProjectTemplate  = \to_system_path($pathToTemplate . "/$projectTemplate");
        $pathToInstallProject   = \to_system_path($rootPath . "/$projectTemplate");


        if (\is_dir($pathToProjectTemplate) === false) {
            $err = "The application template was not found in target directory\n";
            $err .= " [ $pathToProjectTemplate ] \n\n";
            throw new \RuntimeException($err);
        }
        else {
            if (\is_dir($pathToInstallProject) === true) {
                \dir_deltree($pathToInstallProject);
            }

            if (\dir_copy($pathToProjectTemplate, $pathToInstallProject) === false) {
                $err = "Unespected error when copy the template project \"$projectTemplate\" to the root path in:\n";
                $err .= " [ $pathToInstallProject ] \n\n";
                throw new \RuntimeException($err);
            }
            else {
                $startFiles = [
                    ".gitignore"                => ".gitignore",
                    ".htaccess"                 => ".htaccess",
                    "database-config-$env.php"  => "database-config.php",
                    "domain-config-$env.php"    => "domain-config.php",
                    "domain-error.phtml"        => "domain-error.phtml",
                    "index.php"                 => "index.php",
                    "web.config"                => "web.config"
                ];

                foreach ($startFiles as $originName => $destinyName) {
                    $pathToOrigin   = \to_system_path($pathToTemplate . "/$originName");
                    $pathToDestiny  = \to_system_path($rootPath . "/$destinyName");
                    \copy($pathToOrigin, $pathToDestiny);
                }



                echo "::: Instalação dos documentos iniciais executada com sucesso.\n";
                echo ":::\n";
                echo "::: Para finalizar, efetue as edições específicas dos seguintes documentos:\n";
                echo "::: - composer.json\n";
                echo ":::   1. Altere os dados principais do seu projeto.\n";
                echo ":::   2. Adicione na sessão \"autoload\" a diretiva \"classmap\" apontando para o diretorio\n";
                echo ":::      raiz da aplicação instalada.\n";
                echo ":::   3. Rote o comando \"composer update\".\n";
                echo ":::\n";
                echo "::: - Configure o arquivo \"domain-config.php\".\n";
                echo "::: - Configure o arquivo \"database-config.php\".\n";
                echo ":::\n";
                echo "::: Após estes itens o seu projeto está pronto para ser iniciado.\n\n";
            }
        }
    }
}
