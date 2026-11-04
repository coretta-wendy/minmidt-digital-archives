<?php
$required = ['zip', 'xml', 'gd', 'dom', 'zlib', 'xmlreader', 'xmlwriter'];
foreach ($required as $ext) {
    echo "$ext: " . (extension_loaded($ext) ? '✔ OK' : '✖ Manquant') . "<br>";
}