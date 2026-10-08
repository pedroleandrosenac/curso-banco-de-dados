<?php
require __DIR__ . "/../../senac/senac.php";
senacClassName("Banco de Dados — ORDER BY, LIMIT, LIKE e regras da tabela");
?>

<?php senacClassSession("Preparando a tabela de hoje", __LINE__); ?>

    <p>
        Hoje vamos trabalhar com mais usuários, para ver bem a diferença entre
        ordenar, limitar e filtrar. Rode o script abaixo <strong>inteiro</strong>:
        ele apaga a tabela <code>usuarios</code> da aula passada, cria de novo e
        insere 8 usuários.
    </p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
DROP TABLE IF EXISTS usuarios;

CREATE TABLE usuarios(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_completo VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    foto VARCHAR(150),
    eh_admin BOOLEAN NOT NULL DEFAULT FALSE
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
senacAlert("Confira com <code>SELECT * FROM usuarios;</code>: devem aparecer 8 linhas, com ids de 1 a 8.", "accept");
senacAlert("<code>DROP TABLE</code> apaga a tabela <strong>e todos os dados</strong>. Aqui é seguro porque a tabela é de estudo. Em um sistema de verdade, isso não se faz.", "warning");
?>

<?php senacClassSession("ORDER BY — colocando em ordem", __LINE__, "orange"); ?>

<?php senacTag("ORDER BY", null, "https://mariadb.com/kb/en/order-by/"); ?>

    <p>
        Sem pedir, o banco devolve os registros na ordem em que quiser, em geral
        a ordem em que foram inseridos. Para controlar, usamos
        <code>ORDER BY</code>, sempre <strong>no final</strong> do comando.
    </p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
SELECT id, nome_completo FROM usuarios ORDER BY nome_completo;
SQL
        );
        ?>
    </div>

    <div class="tabela-scroll">
        <table class="resultado">
            <thead>
            <tr><th>id</th><th>nome_completo</th></tr>
            </thead>
            <tbody>
            <tr><td>3</td><td>Álvaro Mendes</td></tr>
            <tr><td>1</td><td>Ana Beatriz Silva</td></tr>
            <tr><td>2</td><td>Bruno Costa</td></tr>
            <tr><td>4</td><td>Carla Silva Souza</td></tr>
            <tr><td>5</td><td>Daniel Ferreira</td></tr>
            <tr><td>6</td><td>Eduarda Vitória</td></tr>
            <tr><td>7</td><td>Fábio Silva</td></tr>
            <tr><td>8</td><td>Gabriela Lima</td></tr>
            </tbody>
        </table>
    </div>

<?php
senacAlert("O padrão é <code>ASC</code> (crescente: A a Z, 1 a 9). Não precisa escrever.", "info");
senacAlert("Repare que <strong>Álvaro</strong> veio antes de <strong>Ana</strong>. O banco ignora o acento ao ordenar: compara \"Alvaro\" com \"Ana\", e <code>l</code> vem antes de <code>n</code>.", "info");
?>

    <p>Para inverter, use <code>DESC</code> (decrescente: Z a A, 9 a 1):</p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
SELECT id, nome_completo FROM usuarios ORDER BY nome_completo DESC;
SQL
        );
        ?>
    </div>

    <div class="tabela-scroll">
        <table class="resultado">
            <thead>
            <tr><th>id</th><th>nome_completo</th></tr>
            </thead>
            <tbody>
            <tr><td>8</td><td>Gabriela Lima</td></tr>
            <tr><td>7</td><td>Fábio Silva</td></tr>
            <tr><td>6</td><td>Eduarda Vitória</td></tr>
            <tr><td>5</td><td>Daniel Ferreira</td></tr>
            <tr><td>4</td><td>Carla Silva Souza</td></tr>
            <tr><td>2</td><td>Bruno Costa</td></tr>
            <tr><td>1</td><td>Ana Beatriz Silva</td></tr>
            <tr><td>3</td><td>Álvaro Mendes</td></tr>
            </tbody>
        </table>
    </div>

    <p>
        Dá para ordenar por <strong>duas colunas</strong>. O banco ordena pela
        primeira e, quando há empate, desempata pela segunda. Aqui, os
        administradores primeiro, e dentro de cada grupo, em ordem de nome:
    </p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
SELECT id, nome_completo, eh_admin FROM usuarios
ORDER BY eh_admin DESC, nome_completo;
SQL
        );
        ?>
    </div>

    <div class="tabela-scroll">
        <table class="resultado">
            <thead>
            <tr><th>id</th><th>nome_completo</th><th>eh_admin</th></tr>
            </thead>
            <tbody>
            <tr><td>3</td><td>Álvaro Mendes</td><td>1</td></tr>
            <tr><td>7</td><td>Fábio Silva</td><td>1</td></tr>
            <tr><td>1</td><td>Ana Beatriz Silva</td><td>0</td></tr>
            <tr><td>2</td><td>Bruno Costa</td><td>0</td></tr>
            <tr><td>4</td><td>Carla Silva Souza</td><td>0</td></tr>
            <tr><td>5</td><td>Daniel Ferreira</td><td>0</td></tr>
            <tr><td>6</td><td>Eduarda Vitória</td><td>0</td></tr>
            <tr><td>8</td><td>Gabriela Lima</td><td>0</td></tr>
            </tbody>
        </table>
    </div>

<?php senacAlert("<code>DESC</code> vale só para a coluna que vem antes dele. Em <code>ORDER BY eh_admin DESC, nome_completo</code>, o <code>nome_completo</code> continua crescente.", "info"); ?>

<?php senacClassSession("LIMIT e OFFSET — pegando só uma parte", __LINE__); ?>

<?php senacTag("LIMIT", null, "https://mariadb.com/kb/en/limit/"); ?>

    <p>
        <code>LIMIT</code> diz quantos registros o banco devolve, no máximo.
        Serve para "os 3 primeiros", "os 10 mais recentes" e, principalmente,
        para a <strong>paginação</strong> das telas.
    </p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
SELECT id, nome_completo FROM usuarios ORDER BY nome_completo LIMIT 3;
SQL
        );
        ?>
    </div>

    <div class="tabela-scroll">
        <table class="resultado">
            <thead>
            <tr><th>id</th><th>nome_completo</th></tr>
            </thead>
            <tbody>
            <tr><td>3</td><td>Álvaro Mendes</td></tr>
            <tr><td>1</td><td>Ana Beatriz Silva</td></tr>
            <tr><td>2</td><td>Bruno Costa</td></tr>
            </tbody>
        </table>
    </div>

    <p>
        Para a página 2, pulamos os 3 primeiros com <code>OFFSET</code>
        ("comece depois de quantos registros"):
    </p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
SELECT id, nome_completo FROM usuarios ORDER BY nome_completo LIMIT 3 OFFSET 3;
SQL
        );
        ?>
    </div>

    <div class="tabela-scroll">
        <table class="resultado">
            <thead>
            <tr><th>id</th><th>nome_completo</th></tr>
            </thead>
            <tbody>
            <tr><td>4</td><td>Carla Silva Souza</td></tr>
            <tr><td>5</td><td>Daniel Ferreira</td></tr>
            <tr><td>6</td><td>Eduarda Vitória</td></tr>
            </tbody>
        </table>
    </div>

    <div class="tabela-scroll">
        <table>
            <thead>
            <tr><th>Página</th><th>Comando</th><th>Quem aparece</th></tr>
            </thead>
            <tbody>
            <tr>
                <td>1</td>
                <td><code>LIMIT 3 OFFSET 0</code></td>
                <td>1º, 2º e 3º</td>
            </tr>
            <tr>
                <td>2</td>
                <td><code>LIMIT 3 OFFSET 3</code></td>
                <td>4º, 5º e 6º</td>
            </tr>
            <tr>
                <td>3</td>
                <td><code>LIMIT 3 OFFSET 6</code></td>
                <td>7º e 8º (só sobraram 2)</td>
            </tr>
            </tbody>
        </table>
    </div>

<?php
senacAlert("O <code>OFFSET</code> é sempre <strong>(página - 1) x quantidade por página</strong>. Na página 3: (3 - 1) x 3 = 6.", "info");
senacAlert("Use sempre <code>LIMIT</code> junto com <code>ORDER BY</code>. Sem ordem definida, \"os 3 primeiros\" pode ser qualquer um.", "warning");
?>

<?php senacClassSession("LIKE — procurando por parte do texto", __LINE__, "orange"); ?>

<?php senacTag("LIKE", null, "https://mariadb.com/kb/en/like/"); ?>

    <p>
        Com <code>=</code> o texto tem que ser idêntico. Com <code>LIKE</code>
        procuramos por <strong>pedaço</strong> do texto, usando o curinga
        <code>%</code>, que significa "qualquer coisa, inclusive nada".
    </p>

    <div class="tabela-scroll">
        <table>
            <thead>
            <tr><th>Padrão</th><th>Significa</th></tr>
            </thead>
            <tbody>
            <tr><td><code>'A%'</code></td><td>começa com A</td></tr>
            <tr><td><code>'%a'</code></td><td>termina com a</td></tr>
            <tr><td><code>'%silva%'</code></td><td>tem "silva" em qualquer lugar</td></tr>
            </tbody>
        </table>
    </div>

    <p><strong>Nomes que começam com A:</strong></p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
SELECT id, nome_completo FROM usuarios WHERE nome_completo LIKE 'A%';
SQL
        );
        ?>
    </div>

    <div class="tabela-scroll">
        <table class="resultado">
            <thead>
            <tr><th>id</th><th>nome_completo</th></tr>
            </thead>
            <tbody>
            <tr><td>1</td><td>Ana Beatriz Silva</td></tr>
            <tr><td>3</td><td>Álvaro Mendes</td></tr>
            </tbody>
        </table>
    </div>

    <p><strong>Quem tem "silva" no nome</strong> (é a busca de uma tela de pesquisa):</p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
SELECT id, nome_completo FROM usuarios WHERE nome_completo LIKE '%silva%';
SQL
        );
        ?>
    </div>

    <div class="tabela-scroll">
        <table class="resultado">
            <thead>
            <tr><th>id</th><th>nome_completo</th></tr>
            </thead>
            <tbody>
            <tr><td>1</td><td>Ana Beatriz Silva</td></tr>
            <tr><td>4</td><td>Carla Silva Souza</td></tr>
            <tr><td>7</td><td>Fábio Silva</td></tr>
            </tbody>
        </table>
    </div>

<?php senacAlert("Digitamos <code>silva</code> em minúsculo e achou <code>Silva</code>. O <code>LIKE</code> ignora maiúscula e minúscula.", "info"); ?>

    <p>
        E o acento? Procure por <code>vitoria</code>, sem acento:
    </p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
SELECT id, nome_completo FROM usuarios WHERE nome_completo LIKE '%vitoria%';
SQL
        );
        ?>
    </div>

    <div class="tabela-scroll">
        <table class="resultado">
            <thead>
            <tr><th>id</th><th>nome_completo</th></tr>
            </thead>
            <tbody>
            <tr><td>6</td><td>Eduarda Vitória</td></tr>
            </tbody>
        </table>
    </div>

<?php senacAlert("Achou \"Vitória\" mesmo sem o acento. Isso acontece porque nossas tabelas usam o padrão <code>utf8mb4_unicode_ci</code> (o <code>ci</code> quer dizer \"case insensitive\"). Para o usuário, é ótimo: ninguém precisa lembrar de acentuar para pesquisar.", "accept"); ?>

    <p>
        O <code>LIKE</code> combina com <code>AND</code> e <code>OR</code>.
        Quem tem "silva" no nome <strong>e</strong> é administrador:
    </p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
SELECT id, nome_completo, eh_admin FROM usuarios
WHERE nome_completo LIKE '%silva%' AND eh_admin = TRUE;
SQL
        );
        ?>
    </div>

    <div class="tabela-scroll">
        <table class="resultado">
            <thead>
            <tr><th>id</th><th>nome_completo</th><th>eh_admin</th></tr>
            </thead>
            <tbody>
            <tr><td>7</td><td>Fábio Silva</td><td>1</td></tr>
            </tbody>
        </table>
    </div>

    <p>Agora com <code>OR</code> (tem "silva" <strong>ou</strong> é administrador):</p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
SELECT id, nome_completo, eh_admin FROM usuarios
WHERE nome_completo LIKE '%silva%' OR eh_admin = TRUE;
SQL
        );
        ?>
    </div>

    <div class="tabela-scroll">
        <table class="resultado">
            <thead>
            <tr><th>id</th><th>nome_completo</th><th>eh_admin</th></tr>
            </thead>
            <tbody>
            <tr><td>1</td><td>Ana Beatriz Silva</td><td>0</td></tr>
            <tr><td>3</td><td>Álvaro Mendes</td><td>1</td></tr>
            <tr><td>4</td><td>Carla Silva Souza</td><td>0</td></tr>
            <tr><td>7</td><td>Fábio Silva</td><td>1</td></tr>
            </tbody>
        </table>
    </div>

<?php
senacAlert("<code>AND</code>: as duas condições precisam ser verdadeiras (resultado menor). <code>OR</code>: basta uma (resultado maior).", "info");
?>

<?php senacClassSession("UNIQUE — proibindo repetição", __LINE__); ?>

<?php senacTag("UNIQUE", null, "https://mariadb.com/kb/en/constraint/"); ?>

    <p>
        Na aula passada o banco aceitou dois usuários com o mesmo e-mail. Em um
        sistema de login isso é um problema grave. A regra <code>UNIQUE</code>
        ("único") resolve: o banco não aceita valor repetido naquela coluna.
    </p>

    <p>Como a tabela já existe, adicionamos a regra com <code>ALTER TABLE</code>:</p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
ALTER TABLE usuarios ADD UNIQUE (email);
SQL
        );
        ?>
    </div>

    <p>Agora tente cadastrar de novo um e-mail que já existe:</p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
INSERT INTO usuarios (nome_completo, email, senha)
VALUES ('Outra Ana', 'ana@email.com', '12345abc');
SQL
        );
        ?>
    </div>

<?php
senacAlert("O banco recusa: <code>#1062 - Duplicate entry 'ana@email.com' for key 'email'</code>. Nenhuma linha é criada.", "error");
senacAlert("Se o <code>ALTER TABLE ... ADD UNIQUE</code> der o mesmo erro #1062, é porque a tabela <strong>já tem</strong> e-mails repetidos. Apague ou corrija as linhas repetidas e rode o comando de novo.", "warning");
senacAlert("O <code>INSERT</code> que falhou \"gastou\" o <code>id</code> 9. O próximo usuário cadastrado vai receber o <strong>10</strong>. O banco nunca reaproveita número, nem quando o <code>INSERT</code> dá erro.", "info");
?>

<?php senacClassSession("ALTER TABLE — mudando a tabela depois de pronta", __LINE__, "orange"); ?>

<?php senacTag("ALTER TABLE", null, "https://mariadb.com/kb/en/alter-table/"); ?>

    <p>
        Nenhum projeto nasce com a tabela perfeita. O <code>ALTER TABLE</code>
        muda a <strong>estrutura</strong> da tabela, e os dados que já estão nela
        são mantidos. Os três usos mais comuns:
    </p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
-- 1. Adicionar uma coluna
ALTER TABLE usuarios ADD COLUMN telefone VARCHAR(20);

-- 2. Mudar o tipo ou o tamanho de uma coluna
ALTER TABLE usuarios MODIFY telefone VARCHAR(30);

-- 3. Apagar uma coluna
ALTER TABLE usuarios DROP COLUMN telefone;
SQL
        );
        ?>
    </div>

    <p>
        Rode um de cada vez e use <code>DESCRIBE usuarios;</code> entre eles para
        ver a mudança. Depois do primeiro comando, todos os usuários passam a ter
        a coluna <code>telefone</code>, com valor <code>NULL</code>:
    </p>

    <div class="tabela-scroll">
        <table class="resultado">
            <thead>
            <tr><th>id</th><th>nome_completo</th><th>telefone</th></tr>
            </thead>
            <tbody>
            <tr><td>1</td><td>Ana Beatriz Silva</td><td>NULL</td></tr>
            <tr><td>2</td><td>Bruno Costa</td><td>NULL</td></tr>
            <tr><td>3</td><td>Álvaro Mendes</td><td>NULL</td></tr>
            </tbody>
        </table>
    </div>

<?php
senacAlert("Quem faz <code>DROP COLUMN</code> apaga a coluna <strong>e todos os dados dela</strong>, sem volta. Pense duas vezes antes de rodar numa tabela de verdade.", "error");
senacAlert("Ao adicionar uma coluna <code>NOT NULL</code> em uma tabela que já tem dados, informe um <code>DEFAULT</code>. Senão, o que as linhas antigas vão receber? É o próximo assunto.", "info");
?>

<?php senacClassSession("DEFAULT — o valor automático", __LINE__); ?>

<?php senacTag("DEFAULT", null, "https://mariadb.com/kb/en/create-table/#default"); ?>

    <p>
        O <code>DEFAULT</code> é o valor que a coluna recebe quando ninguém
        informa nada. Já usamos um: <code>eh_admin BOOLEAN NOT NULL DEFAULT FALSE</code>.
        Um uso muito comum é guardar <strong>quando</strong> o registro foi criado:
    </p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
ALTER TABLE usuarios
ADD COLUMN criado_em DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;
SQL
        );
        ?>
    </div>

    <p>
        <code>CURRENT_TIMESTAMP</code> significa "data e hora de agora". Os
        usuários que já existiam recebem a hora em que você rodou o comando, e
        os próximos recebem a hora do próprio <code>INSERT</code>, sem você
        escrever nada:
    </p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
INSERT INTO usuarios (nome_completo, email, senha)
VALUES ('Igor Nunes', 'igor@email.com', '12345abc');
SQL
        );
        ?>
    </div>

    <p>Agora, os cadastrados mais recentes primeiro:</p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
SELECT id, nome_completo, criado_em FROM usuarios
ORDER BY criado_em DESC, id DESC
LIMIT 3;
SQL
        );
        ?>
    </div>

    <div class="tabela-scroll">
        <table class="resultado">
            <thead>
            <tr><th>id</th><th>nome_completo</th><th>criado_em</th></tr>
            </thead>
            <tbody>
            <tr><td>10</td><td>Igor Nunes</td><td>(hora do INSERT)</td></tr>
            <tr><td>8</td><td>Gabriela Lima</td><td>(hora do ALTER)</td></tr>
            <tr><td>7</td><td>Fábio Silva</td><td>(hora do ALTER)</td></tr>
            </tbody>
        </table>
    </div>

<?php
senacAlert("Os usuários antigos têm todos a <strong>mesma hora</strong> (a do <code>ALTER TABLE</code>). Por isso o <code>id DESC</code> no final: é ele que desempata.", "info");
senacAlert("As datas na sua tela serão diferentes das da tabela acima, porque são a hora em que você rodou os comandos. O que precisa bater é a ordem dos nomes.", "accept");
?>

<?php senacClassSession("Onde isso aparece no projeto", __LINE__, "orange"); ?>

    <div class="tabela-scroll">
        <table>
            <thead>
            <tr><th>Recurso</th><th>Exemplo no Janus ou no Cerberus</th></tr>
            </thead>
            <tbody>
            <tr>
                <td><code>ORDER BY</code></td>
                <td>Lista de associados em ordem alfabética; vagas das mais novas para as mais antigas</td>
            </tr>
            <tr>
                <td><code>LIMIT</code> / <code>OFFSET</code></td>
                <td>Listagem com 10 itens por página e botões "anterior" e "próxima"</td>
            </tr>
            <tr>
                <td><code>LIKE</code></td>
                <td>Campo de busca por nome do egresso, do associado ou da vaga</td>
            </tr>
            <tr>
                <td><code>UNIQUE</code></td>
                <td>E-mail de login que não pode se repetir</td>
            </tr>
            <tr>
                <td><code>ALTER TABLE</code></td>
                <td>Descobrir no meio do projeto que faltou uma coluna</td>
            </tr>
            <tr>
                <td><code>DEFAULT</code></td>
                <td>Data de cadastro automática; situação "ativo" por padrão</td>
            </tr>
            </tbody>
        </table>
    </div>

<?php senacClassSession("Resumo", __LINE__); ?>

    <div class="tabela-scroll">
        <table>
            <thead>
            <tr><th>Comando</th><th>Para quê</th></tr>
            </thead>
            <tbody>
            <tr><td><code>ORDER BY coluna [DESC]</code></td><td>Colocar em ordem</td></tr>
            <tr><td><code>LIMIT n OFFSET m</code></td><td>Pegar só uma parte (paginação)</td></tr>
            <tr><td><code>LIKE '%texto%'</code></td><td>Procurar por pedaço do texto</td></tr>
            <tr><td><code>AND</code> / <code>OR</code></td><td>Combinar condições</td></tr>
            <tr><td><code>UNIQUE</code></td><td>Proibir valor repetido na coluna</td></tr>
            <tr><td><code>ALTER TABLE</code></td><td>Adicionar, mudar ou apagar colunas</td></tr>
            <tr><td><code>DEFAULT</code></td><td>Valor automático quando nada é informado</td></tr>
            </tbody>
        </table>
    </div>

<?php senacClassSession("Desafio", __LINE__, "orange"); ?>

    <p>Na tabela <code>usuarios</code> desta aula, escreva os comandos para:</p>

    <ol>
        <li>Listar todos os usuários em ordem alfabética <strong>inversa</strong> (Z a A).</li>
        <li>Listar os 2 usuários com <code>id</code> mais alto.</li>
        <li>Listar quem tem o nome começando com <strong>C</strong> ou com <strong>D</strong>.</li>
        <li>Mostrar a <strong>página 2</strong>, com 4 usuários por página, em ordem de nome.</li>
        <li>Adicionar a coluna <code>cidade VARCHAR(80)</code> com <code>DEFAULT 'Caxias'</code>, e conferir com <code>SELECT</code> que os usuários antigos receberam o valor.</li>
    </ol>

<?php
senacAlert("Dica da questão 4: o <code>OFFSET</code> é (2 - 1) x 4.", "info");
senacAlert("Próxima aula: o PHP conversando com o banco. Vamos conhecer o <strong>PDO</strong> e fazer o primeiro <code>SELECT</code> aparecer em uma página.", "accept");
senacFooter("Pedro Leandro");
?>