<?php

//generer options de taille
function generateSelectOptions($selected = 10): string {
    $html = "";
    $options = range(8, 42);
    $currentSelection = $_POST["size"] ?? $selected;
    foreach ($options as $value) {
        $attribute = "";
        if ((int) $value == (int) $selected) {
            $attribute = "selected";
        }
        $html .= "<option $attribute value=\"$value\">$value</option>";
    }
    return $html;
}


//params
$generated = " ";
$size = $_POST["size"] ?? 10;
$includeMin = isset($_POST["useMin"]);
$includeMaj = isset($_POST["useMaj"]);
$includeNumbers = isset($_POST["useNum"]);
$includeSymbols = isset($_POST["useSym"]);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $isMinChecked = true;
    $isMajChecked = true;
    $isNumberChecked = true;
    $isSymbolChecked = true;
} else {
    $isMinChecked = $includeMin;
    $isMajChecked = $includeMaj;
    $isNumberChecked = $includeNumbers;
    $isSymbolChecked = $includeSymbols;
}

//generate Password
function generatePassword(
    int $size,
    bool $includeMin,
    bool $includeMaj,
    bool $includeNumbers,
    bool $includeSymbols,
): string {
    $allCharacters = '';

    if ($includeMin) {
        $allCharacters .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    }
    if ($includeMaj) {
        $allCharacters .= "abcdefghijkl";
    }
    if ($includeNumbers) {
        $allCharacters .= "0123456789";
    }
    if ($includeSymbols) {
        $allCharacters .= "!@#$%^&*";
    }


    $password = '';
    $charLenght = strlen($allCharacters);

    for ($i = 0; $i < $size; $i++) {
        $randomIndex = rand(0, $charLenght - 1);
        $password .= $allCharacters[$randomIndex];
    }
    return $password;
}

$sequence = [];
if ($isMajChecked) {
    $sequence[] = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
}
if ($isMinChecked) {
    $sequence[] = "abcdefghijklmnopqrstuvwxyz";
}
if ($isNumberChecked) {
    $sequence[] = "0123456789";
}
if ($isSymbolChecked) {
    $sequence[] = "!@#$%^&*()_+=-{}[]:;<>?,./";
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $generated =
}

//doc html
$html = <<< HTML
<!doctype html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Password Generator</title>
    <link rel="stylesheet" href="style.css"> 
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
  <body>
  <div class="container">
    <h1>Password Generator</h1>
    <h2>Mot de passe généré:</h2>
    <div>$generated</div>
        
    <form method = "post" action = "index.php">
        <input type = "checkbox" name = "useMin">Include Uppercase Letters</input>
        <br>
        <input type = "checkbox" name = "useMaj">Include Lowercase Letters</input>
        <br>
        <input type = "checkbox" name = "useNum">Include Numbers</input>
        <br>
        <input type = "checkbox" name = "useSym">Include Symbols</input>
        <br>
        <div>
            <label for = "size" class = "formLabel">Size</label>
            <select class = "sizeSelect" name = "size">
            $selectedOption
            <br>
            </select>
        </div>
        <button type = "submit" class = "bg-blue-500">Generate Password</button>
    </form>
    </div>
  </body>
</html>
HTML;

echo $html;