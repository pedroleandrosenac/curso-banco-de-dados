<?php
require __DIR__ . "/../../senac/senac.php";
senacClassName("Banco de Dados — INSERT, SELECT, UPDATE e DELETE");
?>

<?php senacClassSession("A tabela de hoje", __LINE__, "orange"); ?>

    <p>Hoje usamos <strong>uma tabela só</strong>, a <code>usuarios</code>:</p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
CREATE TABLE usuarios(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_completo VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    foto VARCHAR(150),
    eh_admin BOOLEAN NOT NULL DEFAULT FALSE
);
SQL
        );
        ?>
    </div>

    <div class="tabela-scroll">
        <table>
            <thead>
            <tr>
                <th>Coluna</th>
                <th>O que guarda</th>
                <th>Regra</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><code>id</code></td>
                <td>Número do usuário</td>
                <td>O banco numera sozinho, nunca digitamos</td>
            </tr>
            <tr>
                <td><code>nome_completo</code></td>
                <td>Nome da pessoa</td>
                <td><code>NOT NULL</code>: obrigatório, até 150 caracteres</td>
            </tr>
            <tr>
                <td><code>email</code></td>
                <td>E-mail de login</td>
                <td><code>NOT NULL</code>: obrigatório</td>
            </tr>
            <tr>
                <td><code>senha</code></td>
                <td>Senha</td>
                <td><code>NOT NULL</code>. Tem 255 porque a senha criptografada é longa</td>
            </tr>
            <tr>
                <td><code>foto</code></td>
                <td>Só o <strong>nome do arquivo</strong> da foto</td>
                <td>Sem <code>NOT NULL</code>: pode ficar sem valor (<code>NULL</code>)</td>
            </tr>
            <tr>
                <td><code>eh_admin</code></td>
                <td>Sim ou não: é administrador?</td>
                <td>Se não informarmos, vira <code>FALSE</code></td>
            </tr>
            </tbody>
        </table>
    </div>

<?php
senacAlert("Quem criou a tabela <code>usuarios</code> diferente na aula passada precisa apagar e recriar: rode <code>DROP TABLE IF EXISTS usuarios;</code> e depois o <code>CREATE TABLE</code> acima.", "warning");
senacAlert("<code>BOOLEAN</code> guarda <code>0</code> (falso) e <code>1</code> (verdadeiro). É normal o phpMyAdmin mostrar <code>tinyint(1)</code>.", "info");
senacAlert("<code>NULL</code> quer dizer \"sem valor\", e não é o mesmo que texto vazio (<code>''</code>). Um usuário sem foto tem <code>foto</code> igual a <code>NULL</code>.", "info");
?>

<?php senacClassSession("INSERT — cadastrando usuários", __LINE__); ?>

<?php senacTag("INSERT", null, "https://mariadb.com/kb/en/insert/"); ?>

    <p>
        <code>INSERT</code> coloca um registro novo na tabela. Existem três formas
        de usar:
    </p>

    <p><strong>1. Completo</strong> — todas as colunas, menos o <code>id</code>:</p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
INSERT INTO usuarios (nome_completo, email, senha, foto, eh_admin)
VALUES ('Pedro Leandro', 'pedro@email.com', '12345abc', 'pedro.jpg', FALSE);
SQL
        );
        ?>
    </div>

    <p><strong>2. Omitindo colunas</strong> — as que ficam de fora recebem o valor padrão:</p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
INSERT INTO usuarios (nome_completo, email, senha)
VALUES ('Emily Vitória', 'emily@email.com', '12345abc');
SQL
        );
        ?>
    </div>

    <p>
        Aqui a <code>foto</code> ficou <code>NULL</code> e o <code>eh_admin</code>
        ficou <code>FALSE</code> (o <code>DEFAULT</code> da tabela).
    </p>

    <p><strong>3. Várias linhas de uma vez</strong>:</p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
INSERT INTO usuarios (nome_completo, email, senha, foto, eh_admin) VALUES
('Walyson Pinheiro', 'waly@email.com', '12345abc', 'waly.png', FALSE),
('Amaury Damasceno', 'amaury@email.com', '12345abc', NULL, TRUE),
('Hellison Soares', 'hellison@email.com', '12345abc', NULL, FALSE);
SQL
        );
        ?>
    </div>

    <ul>
        <li>Texto vai entre <strong>aspas simples</strong>: <code>'Pedro'</code></li>
        <li><code>TRUE</code>, <code>FALSE</code>, <code>NULL</code> e números vão <strong>sem aspas</strong></li>
        <li>Sempre liste as colunas depois do nome da tabela, assim a ordem dos valores não depende da ordem da tabela</li>
    </ul>

<?php
senacAlert("Confira com <code>SELECT * FROM usuarios;</code>: devem aparecer 5 linhas, com ids de 1 a 5.", "accept");
?>

    <div class="tabela-scroll">
        <table class="resultado">
            <caption>SELECT * FROM usuarios;</caption>
            <thead>
            <tr><th>id</th><th>nome_completo</th><th>email</th><th>senha</th><th>foto</th><th>eh_admin</th></tr>
            </thead>
            <tbody>
            <tr><td>1</td><td>Pedro Leandro</td><td>pedro@email.com</td><td>12345abc</td><td>pedro.jpg</td><td>0</td></tr>
            <tr><td>2</td><td>Emily Vitória</td><td>emily@email.com</td><td>12345abc</td><td>NULL</td><td>0</td></tr>
            <tr><td>3</td><td>Walyson Pinheiro</td><td>waly@email.com</td><td>12345abc</td><td>waly.png</td><td>0</td></tr>
            <tr><td>4</td><td>Amaury Damasceno</td><td>amaury@email.com</td><td>12345abc</td><td>NULL</td><td>1</td></tr>
            <tr><td>5</td><td>Hellison Soares</td><td>hellison@email.com</td><td>12345abc</td><td>NULL</td><td>0</td></tr>
            </tbody>
        </table>
    </div>

<?php
senacAlert("A senha aparece em texto puro só porque estamos estudando. Em um sistema de verdade ela é guardada criptografada, com <code>password_hash</code>, que ainda vamos ver.", "warning");
?>

    <p>E o que acontece se deixarmos de fora algo <strong>obrigatório</strong>?</p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
INSERT INTO usuarios (nome_completo, email, senha)
VALUES (NULL, 'teste@email.com', '12345abc');
SQL
        );
        ?>
    </div>

<?php
senacAlert("O banco recusa: <code>#1048 - Column 'nome_completo' cannot be null</code>. É o <code>NOT NULL</code> protegendo a tabela, e nenhuma linha é criada.", "error");
?>

<?php senacClassSession("SELECT — conferindo e filtrando", __LINE__, "orange"); ?>

<?php senacTag("SELECT", null, "https://mariadb.com/kb/en/select/"); ?>

    <p>
        <code>SELECT</code> lê os dados. Com <code>WHERE</code> escolhemos
        <strong>quais linhas</strong> queremos:
    </p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
SELECT nome_completo, email FROM usuarios;
SELECT * FROM usuarios WHERE eh_admin = TRUE;
SELECT * FROM usuarios WHERE email = 'pedro@email.com';
SELECT nome_completo FROM usuarios WHERE foto IS NULL;
SELECT nome_completo FROM usuarios WHERE foto IS NOT NULL;
SQL
        );
        ?>
    </div>

    <div class="tabela-scroll">
        <table>
            <thead>
            <tr>
                <th>Consulta</th>
                <th>Resultado</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><code>nome_completo, email</code></td>
                <td>5 linhas, só com essas 2 colunas</td>
            </tr>
            <tr>
                <td><code>eh_admin = TRUE</code></td>
                <td>1 linha: Amaury Damasceno</td>
            </tr>
            <tr>
                <td><code>email = 'pedro@email.com'</code></td>
                <td>1 linha: Pedro Leandro (é a consulta que o login vai fazer)</td>
            </tr>
            <tr>
                <td><code>foto IS NULL</code></td>
                <td>3 linhas: Emily, Amaury e Hellison</td>
            </tr>
            <tr>
                <td><code>foto IS NOT NULL</code></td>
                <td>2 linhas: Pedro e Walyson</td>
            </tr>
            </tbody>
        </table>
    </div>

<?php
senacAlert("Para procurar <code>NULL</code>, use <code>IS NULL</code>. Escrever <code>foto = NULL</code> não dá erro, mas também não encontra nada, porque <code>NULL</code> não é igual a coisa nenhuma.", "warning");
?>

<?php senacClassSession("UPDATE — alterando dados", __LINE__); ?>

<?php senacTag("UPDATE", null, "https://mariadb.com/kb/en/update/"); ?>

    <p>
        <code>UPDATE</code> muda dados que já existem. O formato é:
        <code>UPDATE tabela SET coluna = valor WHERE condição</code>
    </p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
UPDATE usuarios SET foto = 'emily.jpg' WHERE email = 'emily@email.com';

UPDATE usuarios SET eh_admin = TRUE WHERE id = 3;

UPDATE usuarios SET nome_completo = 'Walyson Pinheiro Costa', foto = NULL WHERE id = 3;
SQL
        );
        ?>
    </div>

    <ul>
        <li>O <code>WHERE</code> escolhe <strong>qual linha</strong> muda. Sem ele, <strong>todas</strong> mudam</li>
        <li>Para trocar várias colunas de uma vez, separe com vírgula no <code>SET</code></li>
        <li>O phpMyAdmin informa quantas linhas foram afetadas. Aqui, cada comando afeta <strong>1</strong></li>
    </ul>

    <div class="tabela-scroll">
        <table class="resultado">
            <caption>Como a tabela fica depois dos três UPDATE</caption>
            <thead>
            <tr><th>id</th><th>nome_completo</th><th>foto</th><th>eh_admin</th></tr>
            </thead>
            <tbody>
            <tr><td>1</td><td>Pedro Leandro</td><td>pedro.jpg</td><td>0</td></tr>
            <tr><td>2</td><td>Emily Vitória</td><td>emily.jpg</td><td>0</td></tr>
            <tr><td>3</td><td>Walyson Pinheiro Costa</td><td>NULL</td><td>1</td></tr>
            <tr><td>4</td><td>Amaury Damasceno</td><td>NULL</td><td>1</td></tr>
            <tr><td>5</td><td>Hellison Soares</td><td>NULL</td><td>0</td></tr>
            </tbody>
        </table>
    </div>

<?php
senacAlert("Rodou o mesmo <code>UPDATE</code> duas vezes e na segunda apareceu <strong>0 linhas afetadas</strong>? Não é erro: o valor já era aquele, então nada mudou.", "info");
?>

<?php senacClassSession("DELETE — apagando dados", __LINE__, "orange"); ?>

<?php senacTag("DELETE", null, "https://mariadb.com/kb/en/delete/"); ?>

    <p>
        <code>DELETE</code> apaga linhas. Um hábito que evita acidente:
        <strong>primeiro faça o <code>SELECT</code> com o mesmo
            <code>WHERE</code></strong>, confira o que vai sumir, e só então troque
        por <code>DELETE</code>.
    </p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
SELECT * FROM usuarios WHERE id = 5;   -- confere o que vai sumir
DELETE FROM usuarios WHERE id = 5;     -- apaga
SQL
        );
        ?>
    </div>

    <p>O <code>id</code> apagado não volta. Cadastre um usuário novo e veja qual número ele recebe:</p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
INSERT INTO usuarios (nome_completo, email, senha)
VALUES ('Novo Usuario', 'novo@email.com', '12345abc');

SELECT id, nome_completo FROM usuarios;
SQL
        );
        ?>
    </div>

    <div class="tabela-scroll">
        <table class="resultado">
            <caption>Os ids não são reaproveitados</caption>
            <thead>
            <tr><th>id</th><th>nome_completo</th></tr>
            </thead>
            <tbody>
            <tr><td>1</td><td>Pedro Leandro</td></tr>
            <tr><td>2</td><td>Emily Vitória</td></tr>
            <tr><td>3</td><td>Walyson Pinheiro Costa</td></tr>
            <tr><td>4</td><td>Amaury Damasceno</td></tr>
            <tr><td>6</td><td>Novo Usuario</td></tr>
            </tbody>
        </table>
    </div>

<?php
senacAlert("O novo usuário recebeu o <strong>6</strong>, e não o 5. O banco nunca reaproveita um número de <code>id</code>, e por isso o <code>id</code> é uma identificação segura.", "info");
?>

<?php senacClassSession("O perigo do comando sem WHERE", __LINE__); ?>

    <p>
        <code>UPDATE</code> e <code>DELETE</code> <strong>sem
            <code>WHERE</code></strong> atingem a tabela inteira. Para ver isso sem
        risco, vamos usar uma <strong>cópia</strong> da tabela:
    </p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
CREATE TABLE usuarios_teste LIKE usuarios;
INSERT INTO usuarios_teste SELECT * FROM usuarios;

UPDATE usuarios_teste SET foto = 'todos.jpg';
SELECT id, foto FROM usuarios_teste;

DELETE FROM usuarios_teste;
SELECT * FROM usuarios_teste;

DROP TABLE usuarios_teste;
SQL
        );
        ?>
    </div>

    <ul>
        <li>As duas primeiras linhas só criam a cópia, é só copiar e colar</li>
        <li>O <code>UPDATE</code> sem <code>WHERE</code> trocou a foto de <strong>todos os 5</strong> usuários</li>
        <li>O <code>DELETE</code> sem <code>WHERE</code> deixou a tabela <strong>vazia</strong></li>
    </ul>

<?php
senacAlert("Nunca rode <code>UPDATE</code> ou <code>DELETE</code> sem <code>WHERE</code> na tabela de verdade. Em um sistema real, não existe botão de desfazer.", "error");
?>

<?php senacClassSession("Um problema que vamos resolver na próxima aula", __LINE__, "orange"); ?>

    <p>O que acontece se dois usuários tiverem o <strong>mesmo e-mail</strong>?</p>

    <div class="code">
        <?php
        echo htmlspecialchars(<<<'SQL'
INSERT INTO usuarios (nome_completo, email, senha)
VALUES ('Emily Duplicada', 'emily@email.com', '12345abc');

UPDATE usuarios SET senha = 'nova123' WHERE email = 'emily@email.com';

DELETE FROM usuarios WHERE nome_completo = 'Emily Duplicada';

UPDATE usuarios SET senha = '12345abc' WHERE id = 2;
SQL
        );
        ?>
    </div>

<?php
senacAlert("O banco aceitou o e-mail repetido, e o <code>UPDATE</code> afetou <strong>2 linhas</strong> em vez de 1: trocou a senha das duas Emily. No login isso seria um problema sério. A solução é o <code>UNIQUE</code>, que vemos na próxima aula.", "warning");
senacAlert("Os dois últimos comandos arrumam a bagunça: o <code>DELETE</code> apaga a Emily duplicada, e o último <code>UPDATE</code> devolve a senha da Emily original, desta vez escolhendo a linha pelo <code>id</code>, que é único.", "info");
?>

<?php senacClassSession("Resumo dos quatro comandos", __LINE__); ?>

    <div class="tabela-scroll">
        <table>
            <thead>
            <tr>
                <th>Comando</th>
                <th>Para que serve</th>
                <th>Formato</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td><code>INSERT</code></td>
                <td>Cadastrar</td>
                <td><code>INSERT INTO tabela (colunas) VALUES (valores)</code></td>
            </tr>
            <tr>
                <td><code>SELECT</code></td>
                <td>Consultar</td>
                <td><code>SELECT colunas FROM tabela WHERE condição</code></td>
            </tr>
            <tr>
                <td><code>UPDATE</code></td>
                <td>Alterar</td>
                <td><code>UPDATE tabela SET coluna = valor WHERE condição</code></td>
            </tr>
            <tr>
                <td><code>DELETE</code></td>
                <td>Apagar</td>
                <td><code>DELETE FROM tabela WHERE condição</code></td>
            </tr>
            </tbody>
        </table>
    </div>

<?php
senacAlert("Os quatro juntos formam o que se chama de <strong>CRUD</strong>, em inglês: Create, Read, Update, Delete. É a base de quase todo sistema, inclusive o Janus e o Cerberus.", "accept");
?>

<?php senacClassSession("Desafio", __LINE__, "orange"); ?>

    <p>Na tabela <code>usuarios</code>, escreva o comando de cada tarefa, sem exemplo pronto:</p>

    <ol>
        <li>Cadastre <strong>2 usuários novos</strong>: um administrador com foto e um comum sem foto</li>
        <li>Corrija o <strong>e-mail</strong> de um usuário, escolhendo a linha pelo <code>id</code></li>
        <li>Transforme um usuário comum em <strong>administrador</strong></li>
        <li>Apague um usuário que você cadastrou <strong>por engano</strong></li>
        <li>Liste todos os usuários que <strong>ainda não têm foto</strong></li>
    </ol>

<?php
senacAlert("Depois de cada tarefa, confira o resultado com um <code>SELECT</code> e só então passe para a próxima.", "accept");
senacAlert("Próxima aula: <code>ORDER BY</code>, <code>LIMIT</code>, <code>LIKE</code> e as regras da tabela (<code>UNIQUE</code>, <code>DEFAULT</code> e <code>ALTER TABLE</code>).", "info");
senacFooter("Pedro Leandro");
?>