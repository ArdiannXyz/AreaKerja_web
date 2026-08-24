<?php
$dir = __DIR__ . '/resources/views/perusahaan';
$files = scandir($dir);
echo "FILES IN resources/views/perusahaan:\n";
foreach ($files as $f) {
    if ($f !== '.' && $f !== '..') {
        echo "- $f\n";
    }
}
