<?php
$content = file_get_contents(__DIR__ . '/resources/views/non-user/lowongan-detail.blade.php');
$lines = explode("\n", $content);

$stack = [];
foreach ($lines as $num => $line) {
    $lineNum = $num + 1;
    
    // Check directives
    preg_match_all('/@(if|elseif|else|endif|auth|endauth|guest|endguest|forelse|empty|endforelse|foreach|endforeach)/i', $line, $matches);
    if (!empty($matches[1])) {
        foreach ($matches[1] as $dir) {
            $dir = strtolower($dir);
            echo "Line {$lineNum}: @{$dir}\n";
        }
    }
}
