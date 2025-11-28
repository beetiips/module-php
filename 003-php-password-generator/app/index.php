<?php

function buildCheckboxHtml(string $name, string $label, bool $isChecked): string {
    $checkedAttribute = $isChecked ? 'checked' : '';
    // Structure HTML valide pour les checkboxes
    return "
        <label class='checkbox-line'>
            <input type='checkbox' name='$name' $checkedAttribute>
            $label
        </label>
    ";
}

//generer options de taille
function generateSelectOptions($selected = 10): string {
    $html = "";
    $options = range(8, 42);
    $currentSelection = $_POST["size"] ?? $selected;
    foreach ($options as $value) {
        $attribute = "";
        if ((int) $value == (int) $currentSelection) {
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
        $allCharacters .= "abcdefghijklmnopqrstuvwxyz";
    }
    if ($includeNumbers) {
        $allCharacters .= "0123456789";
    }
    if ($includeSymbols) {
        $allCharacters .= "!@#$%^&*";
    }


    $password = '';
    $charLength = strlen($allCharacters);

    for ($i = 0; $i < $size; $i++) {
        $randomIndex = rand(0, $charLength - 1);
        $password .= $allCharacters[$randomIndex];
    }
    return $password;
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $generated = generatePassword($size, $isMinChecked, $isMajChecked, $isNumberChecked, $isSymbolChecked);
} else {
    $generated = generatePassword($size, $isMinChecked, $isMajChecked, $isNumberChecked, $isSymbolChecked);
}

$selectedOption = generateSelectOptions($size);

$checkboxMin = buildCheckboxHtml("useMin", "Include Uppercase Letters", $isMinChecked);
$checkboxMaj = buildCheckboxHtml("useMaj", "Include Lowercase Letters", $isMajChecked);
$checkboxNum = buildCheckboxHtml("useNum", "Include Numbers", $isNumberChecked);
$checkboxSym = buildCheckboxHtml("useSym", "Include Symbols", $isSymbolChecked);

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
        {$checkboxMin}
        {$checkboxMaj}
        {$checkboxNum}
        {$checkboxSym}
        <div>
            <label for = "size" class = "formLabel">Size</label>
            <select class = "sizeSelect" name = "size">
            $selectedOption
            </select>
        </div>
        <button type = "submit" class = "bg-blue-500">Generate Password</button>
    </form>
    </div>
  </body>
</html>
HTML;

echo $html;