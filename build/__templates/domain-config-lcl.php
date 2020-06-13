<?php
declare (strict_types=1);







/**
 * Indica se deve forçar a navegação usando HTTPS.
 *
 * @var     bool
 */
const FORCE_HTTPS = false;
/**
 * Informa o tipo de ambiente onde a aplicação está rodando no momento.
 *
 * Valores comuns:
 *  - ``PRD``   : Production
 *  - ``HML``   : Homolog
 *  - ``QA``    : Quality Assurance
 *  - ``DEV``   : Development
 *  - ``LCL``   : Local
 *  - ``UTEST`` : Unit Test
 *
 * @var     string
 */
const ENVIRONMENT = "LCL";
/**
 * Indica se a aplicação está em debug mode.
 *
 * Sua principal função é configurar saidas de erros ou retorno de dados
 * para auxilio dos desenvolvedores.
 *
 * @var     bool
 */
const DEBUG_MODE = true;
/**
 * Quando "true" irá refazer o arquivo de indexação das rotas
 * a cada requisição realizada.
 *
 * @var     bool
 */
const UPDATE_ROUTES = true;





/**
 * Array contendo o nome de cada aplicação que está hospedada no
 * domínio.
 *
 * @var     array
 */
const HOSTED_APPS = ["projectName"];
/**
 * Nome da aplicação padrão para o domínio atual.
 *
 * @var     string
 */
const DEFAULT_APP = "projectName";





/**
 * Define o timezone do domínio.
 * [Lista de fusos horários suportados](http://php.net/manual/en/timezones.php)
 *
 * @var     string
 */
const DATETIME_LOCAL = "America/Sao_Paulo";
/**
 * Define o tempo máximo (em segundos) para a execução das requisições.
 *
 * @var     integer
 */
const REQUEST_TIMEOUT = 1200;
/**
 * Define o valor máximo (em Mb) para o upload de um arquivo.
 *
 * @var     integer
 */
const REQUEST_MAX_FILESIZE = 100;
/**
 * Define o valor máximo (em Mb) para a postagem de dados.
 *
 * @var     integer
 */
const REQUEST_MAX_POSTSIZE = 100;





/**
 * Página de erros que deve ser mostrada para o UA caso
 * nenhuma outra tenha sido definida pela aplicação que
 * deve ser executada.
 *
 * O caminho deve ser definido a partir do diretório
 * raiz do domínio.
 *
 * @var     string
 */
const DEFAULT_ERROR_VIEW = "/domain-error.phtml";




/**
 * Nome das classes que iniciam as aplicações do domínio.
 *
 * @var     string
 */
const APPLICATION_CLASSNAME = "AppStart";





/**
 * Carrega o AutoLoader do Composer
 * Registra os namespaces das aplicações do domínio.
 */
$loader = require "vendor/autoload.php";
foreach (HOSTED_APPS as $appName) {
    $appPath = __DIR__ . DS . $appName . DS;
    $loader->addPsr4("$appName\\", $appPath);
    $loader->addPsr4("$appName\\controllers\\", $appPath . "controllers" . DS);
    $loader->addPsr4("$appName\\middlewares\\", $appPath . "middlewares" . DS);
}
