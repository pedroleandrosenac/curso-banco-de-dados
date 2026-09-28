<?php
require __DIR__ . "/../../senac/senac.php";
senacClassName("Banco de Dados — Introdução");
?>

<?php senacClassSession("Por que precisamos de um banco de dados", __LINE__); ?>

<p>
    Até agora, todo dado dos nossos sistemas morava dentro de um
    <strong>array no código</strong>. O cadastro funcionava, mas assim que a
    página era recarregada o registro desaparecia — o array só existia
    durante aquela requisição.
</p>

<p>
    Um <strong>banco de dados</strong> guarda as informações de forma
    permanente, fora do código, e permite que várias páginas e vários
    usuários consultem os mesmos dados.
</p>

<ul>
    <li><strong>Janus:</strong> o egresso se cadastra hoje e continua cadastrado amanhã</li>
    <li><strong>Cerberus:</strong> a lista de convidados deixa de ficar em fichas de papel e passa a ficar guardada no sistema</li>
</ul>

<?php senacClassSession("Ferramentas que vamos usar", __LINE__, "orange"); ?>

<?php
senacTag("XAMPP", null, "https://www.apachefriends.org");
senacTag("phpMyAdmin", null, "https://www.phpmyadmin.net");
senacTag("MySQL Workbench", null, "https://dev.mysql.com/downloads/workbench/");
?>

<table>
    <thead>
    <tr>
        <th>Ferramenta</th>
        <th>Para que serve</th>
        <th>Instalar?</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><strong>XAMPP</strong></td>
        <td>Instala de uma vez o Apache, o PHP e o servidor de banco de dados</td>
        <td>Já está instalado</td>
    </tr>
    <tr>
        <td><strong>MySQL / MariaDB</strong></td>
        <td>O servidor de banco de dados em si</td>
        <td>Vem no XAMPP</td>
    </tr>
    <tr>
        <td><strong>phpMyAdmin</strong></td>
        <td>Painel web para criar e gerenciar bancos, sem usar terminal</td>
        <td>Vem no XAMPP</td>
    </tr>
    <tr>
        <td><strong>MySQL Workbench</strong></td>
        <td>Programa de computador alternativo ao phpMyAdmin</td>
        <td>Opcional — não é necessário nas próximas aulas</td>
    </tr>
    </tbody>
</table>

<?php
senacAlert("O XAMPP usa o <strong>MariaDB</strong>, uma versão compatível com o MySQL. Os comandos que vamos ver são exatamente os mesmos — se aparecer 'MariaDB' na tela, é normal.", "info");
?>

<?php senacClassSession("Verificando o ambiente", __LINE__); ?>

<ol>
    <li>Abra o <strong>XAMPP Control Panel</strong></li>
    <li>Clique em <strong>Start</strong> ao lado de <strong>Apache</strong> e de <strong>MySQL</strong> — os dois nomes ficam com fundo verde</li>
    <li>No navegador, acesse <code>http://localhost/phpmyadmin</code></li>
    <li>O painel deve abrir, com uma lista de bancos já existentes na coluna da esquerda (não mexa neles)</li>
</ol>

<?php
senacAlert("O MySQL não ficou verde? A causa mais comum é a porta <code>3306</code> já estar sendo usada por outro MySQL instalado no computador. Chame o professor antes de mudar qualquer configuração.", "warning");
senacAlert("O phpMyAdmin pediu usuário e senha? No XAMPP o usuário padrão é <code>root</code>, com a senha em branco.", "info");
?>

<?php senacClassSession("Conceitos: banco, tabela, registro e atributo", __LINE__, "orange"); ?>

<p>
    Pense num banco de dados como um <strong>arquivo de planilhas</strong>:
    o banco é o arquivo, e cada planilha é uma <strong>tabela</strong>.
</p>

<div class="tabela-scroll">
    <table class="resultado">
        <caption>Tabela: vagas</caption>
        <thead>
        <tr>
            <th>id</th>
            <th>titulo</th>
            <th>empresa</th>
            <th>area</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>1</td>
            <td>Desenvolvedor PHP Jr.</td>
            <td>Tech Caxias</td>
            <td>Tecnologia</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Assistente Administrativo</td>
            <td>Loja Central</td>
            <td>Administração</td>
        </tr>
        <tr>
            <td>3</td>
            <td>Designer Gráfico</td>
            <td>Studio Norte</td>
            <td>Design</td>
        </tr>
        </tbody>
    </table>
</div>

<ul>
    <li><strong>Tabela</strong> — um conjunto de dados sobre uma mesma coisa (vagas, usuários, convidados)</li>
    <li><strong>Registro</strong> (linha) — um item da tabela; cada vaga cadastrada é um registro</li>
    <li><strong>Atributo</strong> (coluna) — uma informação que todo registro tem: <code>titulo</code>, <code>empresa</code>, <code>area</code></li>
</ul>

<?php senacClassSession("Chave primária e AUTO_INCREMENT", __LINE__); ?>

<p>
    Todo registro precisa de algo que o identifique de forma
    <strong>única</strong>. Esse identificador é a <strong>chave
        primária</strong> — quase sempre uma coluna chamada <code>id</code>.
</p>

<p>
    Com <code>AUTO_INCREMENT</code>, o próprio banco numera os
    registros sozinho (1, 2, 3...) — por isso nunca digitamos o
    <code>id</code> ao cadastrar algo novo.
</p>

<?php
senacAlert("Por que não usar o nome ou o e-mail como identificador? Porque podem se repetir ou mudar. O <code>id</code> nunca muda e nunca se repete.", "accept");
?>

<?php senacClassSession("Tipos de dados", __LINE__, "orange"); ?>

<table>
    <thead>
    <tr>
        <th>Tipo no MySQL</th>
        <th>Parecido com (PHP)</th>
        <th>Usamos para</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><code>INT</code></td>
        <td>int</td>
        <td><code>id</code></td>
    </tr>
    <tr>
        <td><code>VARCHAR(n)</code></td>
        <td>string</td>
        <td>Texto curto: nome, e-mail, título. O <code>n</code> é o tamanho máximo</td>
    </tr>
    <tr>
        <td><code>TEXT</code></td>
        <td>string longa</td>
        <td>Texto longo: descrição, habilidades, observação</td>
    </tr>
    <tr>
        <td><code>DATE</code></td>
        <td>string de data</td>
        <td>Só a data: data da visita</td>
    </tr>
    <tr>
        <td><code>ENUM('a','b')</code></td>
        <td>string com valores fixos</td>
        <td>Só aceita as opções listadas: tipo do usuário</td>
    </tr>
    </tbody>
</table>

<?php
senacAlert("Nomes de tabela e de coluna: tudo minúsculo, com underline no lugar do espaço (<code>area_atuacao</code>, <code>data_visita</code>) — o snake_case do nosso guia de boas práticas.", "info");
senacAlert("A coluna <code>senha</code> já nasce como <code>VARCHAR(255)</code>: no login de verdade a senha será guardada criptografada, e o texto criptografado é longo.", "info");
?>

<?php senacClassSession("Criando o banco e as tabelas no phpMyAdmin", __LINE__); ?>

<ol>
    <li>Na coluna da esquerda, clique em <strong>Novo</strong></li>
    <li>Nome do banco: <code>projeto_integrador</code> — o mesmo nome que já está no <code>config/database.php</code> do projeto. Agrupamento: <code>utf8mb4_unicode_ci</code> (aceita acentos). Clique em <strong>Criar</strong></li>
    <li>Dentro do banco, digite o nome da tabela e o número de colunas, e clique em <strong>Executar</strong></li>
    <li>Preencha cada coluna: nome, tipo e tamanho. No <code>id</code>, marque o índice <strong>PRIMARY</strong> e a caixa <strong>A_I</strong> (AUTO_INCREMENT)</li>
    <li>Clique em <strong>Salvar</strong></li>
</ol>

<p><strong>Janus</strong> — tabelas <code>usuarios</code> e <code>vagas</code>:</p>

<div class="code">
    <?php
    echo htmlspecialchars(<<<'SQL'
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    email VARCHAR(150) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('egresso', 'administrador') NOT NULL,
    curso VARCHAR(100),
    area_atuacao VARCHAR(100),
    habilidades TEXT,
    telefone VARCHAR(20)
);

CREATE TABLE vagas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(150) NOT NULL,
    empresa VARCHAR(150) NOT NULL,
    area VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL
);
SQL
    );
    ?>
</div>

<p><strong>Cerberus</strong> — tabelas <code>associados</code> e <code>convidados</code>:</p>

<div class="code">
    <?php
    echo htmlspecialchars(<<<'SQL'
CREATE TABLE associados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(150) NOT NULL,
    cpf VARCHAR(14) NOT NULL,
    email VARCHAR(150) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    numero_carteirinha VARCHAR(20) NOT NULL,
    plano VARCHAR(50) NOT NULL
);

CREATE TABLE convidados (
    id INT AUTO_INCREMENT PRIMARY KEY,
    associado_id INT NOT NULL,
    nome VARCHAR(150) NOT NULL,
    documento VARCHAR(20) NOT NULL,
    data_visita DATE NOT NULL,
    observacao TEXT
);
SQL
    );
    ?>
</div>

<?php
senacAlert("Esse é o SQL que o phpMyAdmin escreve por baixo dos panos quando você preenche o formulário. Também dá para colar direto na aba <strong>SQL</strong> do banco e executar. <code>NOT NULL</code> significa que o campo não pode ficar vazio.", "info");
senacAlert("Em <code>convidados</code>, <code>associado_id</code> guarda o id do associado que cadastrou aquele convidado. Por enquanto é só um número comum — a ligação entre as tabelas vem depois.", "info");
senacAlert("Prática: crie o banco <code>projeto_integrador</code> e as tabelas do seu projeto. Próxima aula: <code>INSERT</code> e <code>SELECT</code>.", "accept");
senacFooter("Pedro Leandro");
?>
