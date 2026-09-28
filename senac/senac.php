<?php
/**
 * Template de Aulas Senac
 * @author Pedro Leandro Gomes da Silva
 *
 * Document content and charset
 */
header("Content-Type: text/html; charset=utf-8");
/**
 * [ PHP Basic Config ]
 */
date_default_timezone_set("America/Sao_Paulo");
set_error_handler("senacErrorHandler");
/**
 * [ php config ]
 */
ini_set("display_errors", 1);
ini_set("error_reporting", E_ALL);
ini_set('xdebug.overload_var_dump', 1);
/**
 * [ interface ] Style, icon, logo e toggle dark/light
 *
 * O caminho até a pasta /senac/ é calculado automaticamente a partir da
 * raiz do site (document root), então funciona em qualquer profundidade
 * de pasta — não importa se a aula está em /02-variaveis/ (1 nível) ou
 * em /01-introducao-ao-php/01-00-algoritmo/ (2 níveis).
 */
$senacBase = str_replace(realpath($_SERVER['DOCUMENT_ROOT']), '', realpath(__DIR__));
$senacBase = str_replace('\\', '/', $senacBase);
echo "<link rel='stylesheet' href='{$senacBase}/senac.css'/>",
    "<link rel='icon' href='{$senacBase}/Senac_logo.svg.png'/>",
    "<img class='logosenac' src='{$senacBase}/Senac_logo.svg.png'/>",
    "<button id='senac-theme-toggle' onclick='senacToggleTheme()'>🌙 Dark</button>",
    "<script>
        (function() {
            var saved = localStorage.getItem('senac-theme');
            if (saved) {
                document.documentElement.setAttribute('data-theme', saved);
                document.addEventListener('DOMContentLoaded', function() {
                    var btn = document.getElementById('senac-theme-toggle');
                    if (btn) btn.innerHTML = saved === 'dark' ? '☀️ Light' : '🌙 Dark';
                });
            }
        })();
        function senacToggleTheme() {
            var html = document.documentElement;
            var btn  = document.getElementById('senac-theme-toggle');
            if (html.getAttribute('data-theme') === 'dark') {
                html.removeAttribute('data-theme');
                localStorage.setItem('senac-theme', 'light');
                btn.innerHTML = '🌙 Dark';
            } else {
                html.setAttribute('data-theme', 'dark');
                localStorage.setItem('senac-theme', 'dark');
                btn.innerHTML = '☀️ Light';
            }
        }
    </script>";
/**
 * [ Title Function ] Cria o título da aula para o browser
 */
function senacClassName($className)
{
    echo "<title>{$className} | Senac</title>";
}
/**
 * [ Debug session ] Cria uma linha de sessão para exemplos
 * @var $color = red | green | yellow | blue | orange
 * @var $line = __LINE__
 */
function senacClassSession($session, $line, $color = null)
{
    $line    = (!empty($line)    ? " <span class='line-session'>| Linha {$line}</span>" : "");
    $session = (!empty($session) ? "[ {$session}{$line} ]" : "");
    $color   = (!empty($color)   ? "var(--{$color})" : "");
    echo "<div class='code line' style='background-color: {$color}'>{$session}</div>";
}
/**
 * [ Alert ] Exibe um bloco de alerta colorido
 * @var $type = accept | warning | error | info
 * @var $detail = texto secundário opcional (ex: nome do arquivo)
 */
function senacAlert($message, $type = "info", $detail = null)
{
    $detail = (!empty($detail) ? "<small>{$detail}</small>" : "");
    echo "<div class='trigger {$type}'>{$message}{$detail}</div>";
}
/**
 * [ Tag ] Exibe uma badge/tag inline, opcionalmente como link para documentação
 * @var $color = orange | green | red | yellow (vazio = azul padrão)
 * @var $url   = URL da documentação (abre em nova aba)
 */
function senacTag($label, $color = null, $url = null)
{
    $class = (!empty($color) ? "tag {$color}" : "tag");
    if (!empty($url)) {
        echo "<a href='{$url}' target='_blank' class='{$class} tag-link'>{$label} &#x2197;</a>";
    } else {
        echo "<span class='{$class}'>{$label}</span>";
    }
}
/**
 * [ Footer ] Exibe o rodapé padrão da aula
 * @var $instructor = nome do(a) instrutor(a)
 */
function senacFooter($instructor = null)
{
    $by = (!empty($instructor) ? "Instrutor(a): {$instructor} &nbsp;|&nbsp;" : "");
    echo "<div class='senac-footer'>{$by}Senac &copy;" . date("Y") . "</div>";
}
/**
 * [ Default errors ] Função para exibir erros do PHP
 */
function senacErrorHandler($error, $message, $file, $line)
{
    $color = ($error == E_USER_ERROR ? "red" : "yellow");
    echo "<div class='trigger' style='border-color: var(--{$color}); color:var(--{$color});'>[ Linha {$line} ] {$message}<small>{$file}</small></div>";
}