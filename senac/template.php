<?php
require __DIR__ . '/../../senac/senac.php';

// ── Título da aula e subtítulo do módulo ──────────────────────────────────────
senacClassName(
    "Título da Aula",               // ex.: "Variáveis e Tipos de Dados"
    "Módulo 1 – Introdução ao PHP"  // ex.: "Módulo 2 – Funções e Escopo"
);

// ── Sessão 1 ──────────────────────────────────────────────────────────────────
senacClassSession("Conceito X", __LINE__);

/*
 * Seu código / exemplo aqui
 */

// ── Sessão 2 ──────────────────────────────────────────────────────────────────
senacClassSession("Conceito Y", __LINE__, "orange"); // laranja Senac

/*
 * Mais exemplos...
 */

// ── Alertas de exemplo ────────────────────────────────────────────────────────
senacAlert("Atenção: lembre de salvar o arquivo antes de executar.", "warning");
senacAlert("Parabéns! Exercício concluído com sucesso.", "accept");
senacAlert("Erro crítico: conexão recusada.", "error");
senacAlert("Dica: use var_dump() para inspecionar variáveis.", "info");

// ── Rodapé ────────────────────────────────────────────────────────────────────
senacFooter("Seu Nome");
