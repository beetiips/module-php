<?php

$choice = $_GET['choice'] ?? "Please select a choice";
$phpChoice = [
    "Rock",
    "Paper",
    "Scissors"];
$phpRandChoice = array_rand(($phpChoice), 1);
$phpRandomChoice = $phpChoice[$phpRandChoice];

if ($choice == "Please select a choice") {
    $phpRandomChoice = NULL; };

$gameResult = "";

$timesPlayed = " ";
$timesWon = " ";
$timesLost = " ";
$timesDrawed = " ";

if ($choice == $phpRandomChoice) {
    $gameResult = "Draw !";
    //$timesDrawed = $timesDrawed++ ; }//
elseif (($choice == "Rock") && $phpRandomChoice == "Paper") {
    $gameResult = "You lose !"; }
elseif (($choice == "Paper") && $phpRandomChoice == "Scissors") {
    $gameResult = "You lose !"; }
elseif (($choice == "Scissors") && $phpRandomChoice == "Paper") {
    $gameResult = "You win !"; }
elseif (($choice == "Paper") && $phpRandomChoice == "Rock") {
    $gameResult = "You win !"; }
elseif (($choice == "Rock") && $phpRandomChoice == "Scissors") {
    $gameResult = "You win !"; }
elseif (($choice == "Scissors") && $phpRandomChoice == "Rock") {
    $gameResult = "You lose !"; }

$stats = [
    "Times played = $timesPlayed",
    "Times you won = $timesWon",
    "Times you lost = $timesLost",
    "Times you drawed = $timesDrawed",
];

$html = <<<HTML
    <!doctype html>
<html lang="eng">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rock Paper Scissors</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  </head>
  <body>
    <h1>Rock Paper Scissors</h1>
    <h2>Player's choice</h2>
    <div>$choice<br></div>
    <h2>PHP's choice</h2>
    <div>$phpRandomChoice<br></div>
    <h2>Game result</h2>
    <div>$gameResult<br></div>
    <h2>Stats of the game</h2>
    <div>$stats</div>
    <a href="https://localhost?choice=Rock" class="btn btn-primary">Rock</a>
    <a href="https://localhost?choice=Paper" class="btn btn-primary">Paper</a>
    <a href="https://localhost?choice=Scissors" class="btn btn-primary">Scissors</a><br>
    <a href="https://localhost" class="btn btn-primary">Reset game</a>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
  </body>
</html>
HTML;

echo $html;