<?php
/**
 * Credenciais de acesso aos bancos de dados.
 *
 * - A chave principal deve corresponder ao ambiente no qual a aplicação está
 * rodando.
 * - Dentro da chave que identifica o ambiente, cada próxima chave deve ser
 * correspondente ao nome de uma das aplicações instaladas.
 * - Por fim, a terceira chave refere-se ao perfil de usuário que está sendo
 * usado. Neste ponto é que um array associativo deve estar presente contendo
 * todas as credenciais necessárias para efetuar a conexão com o banco de dados.
 *
 * Ex:
 *  [
 *      // 1ª Chave, ambiente [ "UTEST", "LCL", "DEV", "QA", "HML", "PRD" ]
 *      "LCL" => [
 *          // Nome da aplicação tal qual está definida em "HOSTED_APPS"
 *          "applicationName" => [
 *              // Perfil de usuário que está sendo usado pelo UA.
 *              "userProfile" => [
 *              ]
 *          ]
 *      ]
 *  ]
 */
const ENV_DATABASE = [
    "LCL" => [
        "projectName" => [
            "ANONYMOUS" => [
                "dbType"            => "mysql",
                "dbHost"            => "localhost",
                "dbName"            => "database_name",
                "dbUserName"        => "username",
                "dbUserPassword"    => "password",
                "dbSSLCA"           => ""
            ]
        ]
    ]
];
