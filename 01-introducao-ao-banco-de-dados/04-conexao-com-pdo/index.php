<?php
require __DIR__ . "/../../senac/senac.php";
senacClassName("Banco de Dados — Conexão com o PDO");
?>

<?php senacClassSession("Onde estamos", __LINE__); ?>

<p>
    Até agora escrevemos SQL dentro do phpMyAdmin. Só que um sistema de
    verdade não tem alguém digitando comandos: quem conversa com o banco é o
    <strong>PHP</strong>. Hoje vamos ensinar o PHP a se conectar, fazer
    perguntas ao banco e mostrar a resposta em uma página.
</p>

<div class="tabela-scroll">
    <table>
        <thead>
        <tr><th>Antes</th><th>Hoje</th></tr>
        </thead>
        <tbody>
        <tr>
            <td>Pessoa → phpMyAdmin → banco</td>
            <td>Navegador → PHP → <strong>PDO</strong> → banco</td>
        </tr>
        </tbody>
    </table>
</div>

<p>
    O <strong>PDO</strong> (<em>PHP Data Objects</em>) é a "ponte" do PHP até
    o banco. Ele já vem pronto no PHP do XAMPP. Para conferir, crie um arquivo
    com <code>var_dump(extension_loaded('pdo_mysql'));</code>. Se aparecer
    <code>bool(true)</code>, está tudo certo.
</p>

<?php senacClassSession("Preparando o banco de hoje", __LINE__, "orange"); ?>

<p>
    Para todo mundo ter os mesmos dados, rode este script <strong>inteiro</strong>
    na aba <strong>SQL</strong> do phpMyAdmin. Ele cria o banco
    <code>curso_banco</code> (se não existir) e recria a tabela
    <code>usuarios</code> com 8 usuários.
</p>

<div class="code">
    <?php
    echo htmlspecialchars(<<<'SQL'
CREATE DATABASE IF NOT EXISTS curso_banco
    CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

USE curso_banco;

DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_completo VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    foto VARCHAR(150),
    eh_admin BOOLEAN NOT NULL DEFAULT FALSE,
    criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO usuarios (nome_completo, email, senha, foto, eh_admin) VALUES
('Ana Beatriz Silva', 'ana@email.com', '12345abc', 'ana.jpg', FALSE),
('Bruno Costa', 'bruno@email.com', '12345abc', NULL, FALSE),
('Álvaro Mendes', 'alvaro@email.com', '12345abc', NULL, TRUE),
('Carla Silva Souza', 'carla@email.com', '12345abc', 'carla.png', FALSE),
('Daniel Ferreira', 'daniel@email.com', '12345abc', NULL, FALSE),
('Eduarda Vitória', 'eduarda@email.com', '12345abc', 'eduarda.jpg', FALSE),
('Fábio Silva', 'fabio@email.com', '12345abc', NULL, TRUE),
('Gabriela Lima', 'gabriela@email.com', '12345abc', 'gabriela.jpg', FALSE);
SQL
    );
    ?>
</div>

<?php
senacAlert("É a tabela da aula passada, já com o <code>UNIQUE</code> no e-mail e o <code>criado_em</code> com <code>DEFAULT</code>. Confira com <code>SELECT * FROM usuarios;</code>: devem aparecer 8 linhas.", "accept");
senacAlert("No XAMPP, o MySQL precisa estar <strong>ligado</strong> (botão <em>Start</em> no painel, ao lado do Apache). Sem isso, o PHP não consegue conectar.", "warning");
?>

<?php senacClassSession("Os arquivos de hoje", __LINE__); ?>

<p>
    Hoje vocês vão criar arquivos <code>.php</code> <strong>na pasta desta
        aula</strong>, ao lado do <code>index.php</code>. Cada bloco de código
    abaixo começa com um comentário dizendo o nome do arquivo.
</p>

<div class="tabela-scroll">
    <table>
        <thead>
        <tr><th>Arquivo</th><th>Para quê</th></tr>
        </thead>
        <tbody>
        <tr><td><code>primeira-conexao.php</code></td><td>Entender como a conexão funciona</td></tr>
        <tr><td><code>conexao.php</code></td><td>A conexão pronta para reaproveitar</td></tr>
        <tr><td><code>array.php</code></td><td>Ver o que o banco devolve para o PHP</td></tr>
        <tr><td><code>usuarios.php</code></td><td>Mostrar os usuários em uma tabela HTML</td></tr>
        <tr><td><code>usuario.php</code></td><td>Buscar um usuário só</td></tr>
        <tr><td><code>testar-queries.php</code></td><td>Testar qualquer <code>SELECT</code> que a gente inventar</td></tr>
        </tbody>
    </table>
</div>

<?php senacClassSession("A primeira conexão", __LINE__, "orange"); ?>

<?php senacTag("PDO", null, "https://www.php.net/manual/pt_BR/class.pdo.php"); ?>

<div class="code">
    <?php
    echo htmlspecialchars(<<<'CODIGO'
<?php
// primeira-conexao.php

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=curso_banco;charset=utf8mb4",
        "root",
        ""
    );

    echo "Conectou!";
} catch (PDOException $erro) {
    echo "Não conectou: " . $erro->getMessage();
}
CODIGO
    );
    ?>
</div>

<p>
    Abra <code>http://localhost/.../26-conexao-pdo/primeira-conexao.php</code>.
    Se aparecer <strong>Conectou!</strong>, o PHP já está conversando com o
    banco. O <code>new PDO(...)</code> recebe três coisas:
</p>

<div class="tabela-scroll">
    <table>
        <thead>
        <tr><th>Parte</th><th>O que é</th></tr>
        </thead>
        <tbody>
        <tr>
            <td><code>"mysql:host=localhost;dbname=curso_banco;charset=utf8mb4"</code></td>
            <td>O <strong>endereço</strong> (DSN): qual banco (<code>mysql</code>), em qual computador (<code>localhost</code>, o seu), qual banco de dados (<code>curso_banco</code>) e a codificação dos acentos (<code>utf8mb4</code>)</td>
        </tr>
        <tr>
            <td><code>"root"</code></td>
            <td>O usuário do banco. No XAMPP, é o <code>root</code></td>
        </tr>
        <tr>
            <td><code>""</code></td>
            <td>A senha. No XAMPP, é vazia</td>
        </tr>
        </tbody>
    </table>
</div>

<p>
    O <code>try</code> / <code>catch</code> é o "tente, e se der erro, faça
    isso". Dentro do <code>try</code>, o PHP tenta conectar. Se falhar, ele
    pula para o <code>catch</code> em vez de quebrar a página, e o
    <code>getMessage()</code> devolve o motivo do erro.
</p>

<p><strong>Vamos errar de propósito.</strong> Troque <code>curso_banco</code> por <code>banco_que_nao_existe</code> e atualize a página:</p>

<div class="code">
    <?php
    echo htmlspecialchars(<<<'TEXTO'
Não conectou: SQLSTATE[HY000] [1049] Unknown database 'banco_que_nao_existe'
TEXTO
    );
    ?>
</div>

<?php senacAlert("Leia sempre o final da mensagem: <code>Unknown database</code> quer dizer \"banco desconhecido\". Quase todo erro do PDO diz a causa em inglês. Desfaça a troca antes de continuar.", "info"); ?>

<?php senacClassSession("Conexão pronta para reaproveitar", __LINE__); ?>

<p>
    Cada página que usa o banco precisa de uma conexão. Em vez de copiar o
    <code>new PDO(...)</code> em todo arquivo (e ter que mudar tudo se a senha
    mudar), colocamos em uma <strong>função</strong> dentro de um arquivo só.
    Todas as páginas usam essa função.
</p>

<div class="code">
    <?php
    echo htmlspecialchars(<<<'CODIGO'
<?php
// conexao.php

function conectarBanco()
{
    $dsn = "mysql:host=localhost;dbname=curso_banco;charset=utf8mb4";

    $opcoes = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    return new PDO($dsn, "root", "", $opcoes);
}
CODIGO
    );
    ?>
</div>

<div class="tabela-scroll">
    <table>
        <thead>
        <tr><th>Opção</th><th>O que faz</th></tr>
        </thead>
        <tbody>
        <tr>
            <td><code>ERRMODE_EXCEPTION</code></td>
            <td>Quando o SQL der erro, o PHP "lança" o erro e o <code>catch</code> consegue pegar</td>
        </tr>
        <tr>
            <td><code>FETCH_ASSOC</code></td>
            <td>Cada linha do banco chega como um array com o <strong>nome da coluna</strong> (<code>$linha['email']</code>), sem números repetidos</td>
        </tr>
        </tbody>
    </table>
</div>

<?php
senacAlert('Este arquivo só define a função. Não aparece nada na tela quando você abre o <code>conexao.php</code>, e está certo assim. Quem chama <code>conectarBanco()</code> são as outras páginas.', "info");
senacAlert('O nome do arquivo e a função ficam em <code>core/conexao.php</code> no projeto integrador, e os dados de acesso podem ir para <code>config/</code>.', "info");
?>

<?php senacClassSession("O primeiro SELECT pelo PHP", __LINE__, "orange"); ?>

<p>
    Primeiro, vamos só <strong>ver</strong> o que o banco devolve. O
    <code>query()</code> envia o SQL, e o <code>fetchAll()</code> pega todas as
    linhas da resposta.
</p>

<div class="code">
    <?php
    echo htmlspecialchars(<<<'CODIGO'
<?php
// array.php

require_once __DIR__ . "/conexao.php";

$pdo = conectarBanco();

$consulta = $pdo->query("SELECT id, nome_completo FROM usuarios LIMIT 2");
$usuarios = $consulta->fetchAll();

echo "<pre>";
print_r($usuarios);
echo "</pre>";
CODIGO
    );
    ?>
</div>

<div class="code">
    <?php
    echo htmlspecialchars(<<<'TEXTO'
Array
(
    [0] => Array
        (
            [id] => 1
            [nome_completo] => Ana Beatriz Silva
        )

    [1] => Array
        (
            [id] => 2
            [nome_completo] => Bruno Costa
        )

)
TEXTO
    );
    ?>
</div>

<p>
    O resultado é um <strong>array de arrays</strong>: a lista externa tem uma
    posição para cada linha da tabela, e cada linha é um array com os nomes
    das colunas. Como é um array, a gente usa o conhecido <code>foreach</code>.
</p>

<div class="tabela-scroll">
    <table>
        <thead>
        <tr><th>No banco</th><th>No PHP</th></tr>
        </thead>
        <tbody>
        <tr><td>Uma linha da tabela</td><td>Um array: <code>$usuario</code></td></tr>
        <tr><td>Uma coluna da linha</td><td>Uma posição: <code>$usuario['nome_completo']</code></td></tr>
        <tr><td>Todas as linhas</td><td>Uma lista de arrays: <code>$usuarios</code></td></tr>
        </tbody>
    </table>
</div>

<?php senacClassSession("Mostrando os usuários em uma tabela", __LINE__); ?>

<div class="code">
    <?php
    echo htmlspecialchars(<<<'CODIGO'
<?php
// usuarios.php

require_once __DIR__ . "/conexao.php";

try {
    $pdo = conectarBanco();

    $consulta = $pdo->query(
        "SELECT id, nome_completo, email, eh_admin
         FROM usuarios
         ORDER BY nome_completo"
    );
    $usuarios = $consulta->fetchAll();
} catch (PDOException $erro) {
    echo "Erro no banco: " . htmlspecialchars($erro->getMessage());
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Usuários</title>
</head>
<body>
    <h1>Usuários (<?= count($usuarios) ?>)</h1>

    <table border="1" cellpadding="6">
        <tr>
            <th>Id</th>
            <th>Nome</th>
            <th>E-mail</th>
            <th>Administrador?</th>
        </tr>
        <?php foreach ($usuarios as $usuario) { ?>
            <tr>
                <td><?= $usuario['id'] ?></td>
                <td><?= htmlspecialchars($usuario['nome_completo']) ?></td>
                <td><?= htmlspecialchars($usuario['email']) ?></td>
                <td><?= $usuario['eh_admin'] ? "Sim" : "Não" ?></td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
CODIGO
    );
    ?>
</div>

<p>Deve aparecer uma página assim:</p>

<div class="tabela-scroll">
    <table class="resultado">
        <thead>
        <tr><th>Id</th><th>Nome</th><th>E-mail</th><th>Administrador?</th></tr>
        </thead>
        <tbody>
        <tr><td>3</td><td>Álvaro Mendes</td><td>alvaro@email.com</td><td>Sim</td></tr>
        <tr><td>1</td><td>Ana Beatriz Silva</td><td>ana@email.com</td><td>Não</td></tr>
        <tr><td>2</td><td>Bruno Costa</td><td>bruno@email.com</td><td>Não</td></tr>
        <tr><td>4</td><td>Carla Silva Souza</td><td>carla@email.com</td><td>Não</td></tr>
        <tr><td>5</td><td>Daniel Ferreira</td><td>daniel@email.com</td><td>Não</td></tr>
        <tr><td>6</td><td>Eduarda Vitória</td><td>eduarda@email.com</td><td>Não</td></tr>
        <tr><td>7</td><td>Fábio Silva</td><td>fabio@email.com</td><td>Sim</td></tr>
        <tr><td>8</td><td>Gabriela Lima</td><td>gabriela@email.com</td><td>Não</td></tr>
        </tbody>
    </table>
</div>

<?php
senacAlert('A página tem duas partes: em cima, o PHP busca os dados; embaixo, o HTML mostra. Misturar as duas ao mesmo tempo é o que deixa o código bagunçado.', "info");
senacAlert('Todo texto que veio do banco passa por <code>htmlspecialchars()</code>. Se alguém cadastrar o nome <code>&lt;b&gt;Pedro&lt;/b&gt;</code>, a página mostra o texto como ele é e não deixa o HTML rodar. O <code>id</code> e o <code>eh_admin</code> são números que a gente controla.', "warning");
senacAlert('<code>eh_admin</code> chega do banco como <code>0</code> ou <code>1</code>. Por isso o <code>? "Sim" : "Não"</code>, que transforma em palavra.', "info");
?>

<?php senacClassSession("fetchAll() ou fetch()?", __LINE__, "orange"); ?>

<div class="tabela-scroll">
    <table>
        <thead>
        <tr><th>Método</th><th>Devolve</th><th>Quando usar</th></tr>
        </thead>
        <tbody>
        <tr>
            <td><code>fetchAll()</code></td>
            <td>Uma lista com <strong>todas</strong> as linhas (array de arrays)</td>
            <td>Listagens</td>
        </tr>
        <tr>
            <td><code>fetch()</code></td>
            <td><strong>Uma</strong> linha (um array), ou <code>false</code> se não achou</td>
            <td>Buscar um registro só</td>
        </tr>
        </tbody>
    </table>
</div>

<div class="code">
    <?php
    echo htmlspecialchars(<<<'CODIGO'
<?php
// usuario.php

require_once __DIR__ . "/conexao.php";

$pdo = conectarBanco();

$consulta = $pdo->query("SELECT * FROM usuarios WHERE id = 3");
$usuario = $consulta->fetch();

if ($usuario === false) {
    echo "Usuário não encontrado.";
    exit;
}

echo "Nome: " . htmlspecialchars($usuario['nome_completo']) . "<br>";
echo "E-mail: " . htmlspecialchars($usuario['email']);
CODIGO
    );
    ?>
</div>

<div class="code">
    <?php
    echo htmlspecialchars(<<<'TEXTO'
Nome: Álvaro Mendes
E-mail: alvaro@email.com
TEXTO
    );
    ?>
</div>

<?php senacAlert('Troque o <code>3</code> por <code>99</code>: não existe usuário 99, o <code>fetch()</code> devolve <code>false</code> e a página mostra "Usuário não encontrado.". Por isso o <code>if</code> antes de usar a variável.', "info"); ?>

<?php senacClassSession("Testando as consultas juntos", __LINE__); ?>

<p>
    Agora, uma página para <strong>testar qualquer SELECT</strong>: a gente
    troca só o texto da variável <code>$sql</code>, atualiza o navegador e
    vê a tabela de resposta. Ela funciona com qualquer consulta, porque monta
    as colunas sozinha.
</p>

<div class="code">
    <?php
    echo htmlspecialchars(<<<'CODIGO'
<?php
// testar-queries.php

require_once __DIR__ . "/conexao.php";

function mostrarResultado($linhas)
{
    if (count($linhas) === 0) {
        echo "<p>Nenhum resultado.</p>";
        return;
    }

    echo "<table border='1' cellpadding='6'><tr>";
    foreach (array_keys($linhas[0]) as $coluna) {
        echo "<th>" . htmlspecialchars($coluna) . "</th>";
    }
    echo "</tr>";

    foreach ($linhas as $linha) {
        echo "<tr>";
        foreach ($linha as $valor) {
            if ($valor === null) {
                echo "<td><i>NULL</i></td>";
            } else {
                echo "<td>" . htmlspecialchars($valor) . "</td>";
            }
        }
        echo "</tr>";
    }
    echo "</table>";
    echo "<p>" . count($linhas) . " linha(s)</p>";
}

// ===== ESCREVA A CONSULTA AQUI =====
$sql = "SELECT id, nome_completo FROM usuarios ORDER BY nome_completo";
// ===================================

echo "<p><code>" . htmlspecialchars($sql) . "</code></p>";

try {
    $pdo = conectarBanco();
    $linhas = $pdo->query($sql)->fetchAll();
    mostrarResultado($linhas);
} catch (PDOException $erro) {
    echo "<p style='color:red'>Erro: " . htmlspecialchars($erro->getMessage()) . "</p>";
}
CODIGO
    );
    ?>
</div>

<?php
senacAlert('Aqui o SQL é <strong>escrito por nós</strong>, no próprio arquivo, e por isso é seguro. Nunca coloque na <code>$sql</code> algo que veio de um formulário ou da URL (<code>$_POST</code>, <code>$_GET</code>). Na próxima aula veremos o motivo e a forma certa.', "warning");
senacAlert('Use só <code>SELECT</code> nesta página. Um <code>DELETE</code> ou <code>UPDATE</code> digitado aqui roda de verdade no banco.', "error");
?>

<p>Troquem a <code>$sql</code> por cada uma destas e confiram o resultado:</p>

<div class="tabela-scroll">
    <table>
        <thead>
        <tr><th>#</th><th>Valor de <code>$sql</code></th><th>Deve mostrar</th></tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td><code>SELECT id, nome_completo FROM usuarios ORDER BY nome_completo DESC</code></td>
            <td>8 linhas, a Gabriela primeiro</td>
        </tr>
        <tr>
            <td>2</td>
            <td><code>SELECT id, nome_completo FROM usuarios ORDER BY nome_completo LIMIT 3 OFFSET 3</code></td>
            <td>Carla, Daniel e Eduarda</td>
        </tr>
        <tr>
            <td>3</td>
            <td><code>SELECT id, nome_completo FROM usuarios WHERE nome_completo LIKE '%silva%'</code></td>
            <td>3 linhas: Ana, Carla e Fábio</td>
        </tr>
        <tr>
            <td>4</td>
            <td><code>SELECT id, nome_completo, foto FROM usuarios WHERE foto IS NULL</code></td>
            <td>4 linhas, com <i>NULL</i> na coluna foto</td>
        </tr>
        <tr>
            <td>5</td>
            <td><code>SELECT nome_completo FROM usuarios WHERE eh_admin = TRUE</code></td>
            <td>2 linhas: Álvaro e Fábio</td>
        </tr>
        <tr>
            <td>6</td>
            <td><code>SELECT * FROM usuarios WHERE nome_completo LIKE '%xyz%'</code></td>
            <td>"Nenhum resultado."</td>
        </tr>
        <tr>
            <td>7</td>
            <td><code>SELECT * FROM usuario</code> (sem o <code>s</code>)</td>
            <td>Erro em vermelho. Leia o final da mensagem</td>
        </tr>
        </tbody>
    </table>
</div>

<div class="code">
    <?php
    echo htmlspecialchars(<<<'TEXTO'
Erro: SQLSTATE[42S02]: Base table or view not found: 1146 Table 'curso_banco.usuario' doesn't exist
TEXTO
    );
    ?>
</div>

<?php senacAlert('O mesmo SQL que rodava no phpMyAdmin roda aqui, sem mudar nada. O PHP só entrega o texto para o banco e traz a resposta.', "accept"); ?>

<?php senacClassSession("Quando dá erro — o que olhar", __LINE__, "orange"); ?>

<div class="tabela-scroll">
    <table>
        <thead>
        <tr><th>Mensagem</th><th>O que quer dizer</th><th>O que fazer</th></tr>
        </thead>
        <tbody>
        <tr>
            <td><code>Connection refused</code></td>
            <td>O MySQL não está rodando</td>
            <td>Ligue o <em>MySQL</em> no painel do XAMPP</td>
        </tr>
        <tr>
            <td><code>Unknown database 'x'</code></td>
            <td>O <code>dbname</code> está errado, ou o banco não foi criado</td>
            <td>Confira o nome no phpMyAdmin</td>
        </tr>
        <tr>
            <td><code>Access denied for user</code></td>
            <td>Usuário ou senha errados</td>
            <td>No XAMPP: usuário <code>root</code> e senha vazia</td>
        </tr>
        <tr>
            <td><code>could not find driver</code></td>
            <td>O PHP está sem a extensão <code>pdo_mysql</code></td>
            <td>Peça ajuda ao professor</td>
        </tr>
        <tr>
            <td><code>Table '...' doesn't exist</code></td>
            <td>Nome da tabela errado, ou ela não foi criada</td>
            <td>Compare com o nome no phpMyAdmin</td>
        </tr>
        <tr>
            <td><code>You have an error in your SQL syntax</code></td>
            <td>O SQL está escrito errado</td>
            <td>Teste o mesmo SQL no phpMyAdmin e veja onde ele aponta</td>
        </tr>
        </tbody>
    </table>
</div>

<?php senacClassSession("Onde isso aparece no projeto", __LINE__); ?>

<div class="tabela-scroll">
    <table>
        <thead>
        <tr><th>Hoje</th><th>No projeto integrador</th></tr>
        </thead>
        <tbody>
        <tr>
            <td><code>conexao.php</code> com <code>conectarBanco()</code></td>
            <td><code>core/conexao.php</code>, usado por todos os controllers</td>
        </tr>
        <tr>
            <td><code>usuarios.php</code> (busca + HTML)</td>
            <td>Telas de listagem: egressos, vagas, associados</td>
        </tr>
        <tr>
            <td><code>fetch()</code> de um registro</td>
            <td>Tela de detalhes e tela de edição</td>
        </tr>
        <tr>
            <td><code>try</code> / <code>catch</code></td>
            <td>Mostrar uma mensagem amigável quando o banco está fora do ar</td>
        </tr>
        </tbody>
    </table>
</div>

<?php senacClassSession("Resumo", __LINE__, "orange"); ?>

<div class="tabela-scroll">
    <table>
        <thead>
        <tr><th>Comando</th><th>Para quê</th></tr>
        </thead>
        <tbody>
        <tr><td><code>new PDO($dsn, $usuario, $senha)</code></td><td>Conectar o PHP ao banco</td></tr>
        <tr><td><code>try</code> / <code>catch (PDOException $erro)</code></td><td>Pegar o erro sem quebrar a página</td></tr>
        <tr><td><code>$pdo->query($sql)</code></td><td>Enviar um <code>SELECT</code> para o banco</td></tr>
        <tr><td><code>fetchAll()</code></td><td>Todas as linhas: array de arrays</td></tr>
        <tr><td><code>fetch()</code></td><td>Uma linha, ou <code>false</code> se não achou</td></tr>
        <tr><td><code>htmlspecialchars()</code></td><td>Mostrar na tela com segurança o texto que veio do banco</td></tr>
        <tr><td><code>require_once __DIR__ . "/conexao.php"</code></td><td>Reaproveitar a conexão em qualquer página</td></tr>
        </tbody>
    </table>
</div>

<?php
senacAlert("Próxima aula: <strong>INSERT, UPDATE e DELETE pelo PHP</strong>, com formulário. E o motivo de nunca colocar dado do usuário direto no SQL: a <strong>SQL Injection</strong>, e a forma certa de se proteger (<em>prepared statements</em>).", "accept");
senacFooter("Pedro Leandro");
?>
