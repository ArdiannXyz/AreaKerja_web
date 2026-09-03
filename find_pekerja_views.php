<?php
function getBladeFiles($dir) {
    $results = [];
    $files = scandir($dir);
    foreach ($files as $f) {
        if ($f === '.' || $f === '..') continue;
        $path = $dir . '/' . $f;
        if (is_dir($path)) {
            $results = array_merge($results, getBladeFiles($path));
        } else if (str_ends_with($f, '.blade.php')) {
            $results[] = str_replace(__DIR__ . '/resources/views/', '', $path);
        }
    }
    return $results;
}

$views = getBladeFiles(__DIR__ . '/resources/views');
echo "ALL BLADE VIEWS IN PROJECT:\n";
foreach ($views as $v) {
    if (str_contains($v, 'pekerja') || str_contains($v, 'perusahaan') || str_contains($v, 'laporan') || str_contains($v, 'cari')) {
        echo "- $v\n";
    }
}
