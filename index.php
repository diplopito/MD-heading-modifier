<?php
/**
 * =========
 * UC script
 * =========
 * Reads all the md files in a folder and sets to caps the first letters of H1 and H2,
 * excluding words in CONST EXCLUDE
 * 
 * Use boolean flag to update md file
 */

/* Set the dir to scan */
$dir = "F:/xampp/htdocs/xtest/phalconucdocs4";

/* Set flag to modify md file */
const FLAG_REPLACE = false;

/* Words to exclude from capitalization */
const EXCLUDE = [
    "a" => 1,
    "and" => 1,
    "as" => 1,
    "be" => 1,
    "can" => 1,
    "in" => 1,
    "into" => 1,
    "is" => 1,
    "of" => 1,
    "on" => 1,
    "or" => 1,
    "our" => 1,
    "out" => 1,
    "over" => 1,
    "own" => 1,
    "script" => 1,
    "small" => 1,
    "that" => 1,
    "the" => 1,
    "to" => 1,
    "using" => 1,
    "v4" => 1,
    "vs" => 1,
    "which" => 1,
    "with" => 1,
    "your" => 1
];

$docs = scandir($dir);

foreach ($docs as $key => $value) {
    $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
    if (is_file($path) && (substr(strtolower($path), -3) === ".md")) {
        processFile($path);
    }
}

echo "<BR>Finished!";

function processFile($file) {
    $fh = fopen($file, 'rb');
    $log = [];
    $newFile = "";
    
    if (! $fh) {
        $log[] = "Error opening $file";
    }
    
    while (! feof($fh)) {
        $line = fgets($fh); 
   
        if (substr($line, 0, 2) === "# " || substr($line, 0, 3) === "## ") {
            $text = explode(" ", $line);
            if (count($text) > 2) {
                $newLine = ucLine($text);
                $newFile .= $newLine;
                if ($line <> $newLine) {
                    $log[] = $line . " replaced by " . $newLine;  
                }
            } else {
                $newFile .= $line;
            }
        } else {
            $newFile .= $line;
        }
    }

    fclose($fh);

    if (count($log) > 0) {

        if (FLAG_REPLACE) {
            file_put_contents($file, $newFile);
        }
        echo $file . "<BR>";
        print("<pre>".print_r($log,true)."</pre><br>");     
    }
}

function ucLine($text) {
    
    $tmp = [];

    foreach($text as $t) {
        if (isset(EXCLUDE[$t])) {
            $tmp[] = $t;
        } else {
            $tmp[] = ucfirst($t);
        }
    }

    return implode(" ", $tmp);
}
