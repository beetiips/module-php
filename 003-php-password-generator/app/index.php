<?php

//generer options de taille
function generateSelectOptions($selected = 10): string {
    $html = "";
    $options = range(8, 42);
    foreach ($options as $value) {
        $attribute = "";
        if ((int) $value == (int) $selected) {
            $attribute = "selected";
        }
        $html .= "<option $attribute value=\"$value\">$value</option>";
    }
    return $html;
}
$selectedOption = generateSelectOptions();


//params
$generated = " ";
$size = $_POST["size"] ?? 10;
$includeMin = $_POST["useMin"] ?? 0;
$includeMaj = $_POST["useMaj"] ?? 0;
$includeNumbers = $_POST["useNum"] ?? 0;
$includeSymbols = $_POST["useSym"] ?? 0;
$isMinChecked = $includeMin ?? 1 == "checked";
$isMajChecked = $includeMaj ?? 1 == "checked";
$isNumberChecked = $includeNumbers ?? 1 == "checked";
$isSymbolChecked = $includeSymbols ?? 1 == "checked";

//generate Password
function generatePassword(
    int $size,
    bool $includeMin,
    bool $includeMaj,
    bool $includeNumbers,
    bool $includeSymbols,
): string {

}

$sequence = [];
    if ($isMajChecked == 1) {
        $sequence[] = ;
    }
    if ($isMinChecked == 1) {
        $sequence[] = ;
    }
    if ($isNumberChecked == 1) {
        $sequence[] = ;
    }
    if ($isSymbolChecked == 1) {
        $sequence[] = ;
    }













if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $generated = generatePassword($size, $includeMin, $includeMaj, $includeNumbers, $includeSymbols);
} else {
    $includeMin = 1;
    $includeMaj = 1;
    $includeNumbers = 1;
    $includeSymbols = 1;
}


//doc html
$html = <<< HTML
<!doctype html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Password Generator</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
  </head>
  <body>
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
  </body>
</html>
HTML;

echo $html;