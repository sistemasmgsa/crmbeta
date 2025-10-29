<?php
$file = 'db/database.sql';
$content = file_get_contents($file);
$content = preg_replace('/^--.*$/m', '', $content);
$content = preg_replace('/^\\/\\*!.*\\*\\/;$/m', '', $content);
$content = preg_replace('/DELIMITER\s*\S+/s', '', $content);
$content = preg_replace('/DEFINER\s*=\s*`[^`]+`@`[^`]+`/s', '', $content);
$content = preg_replace('/DEFINER\s*=\s*\'[^\']+\'@\'[^\']+\'/s', '', $content);
file_put_contents($file, $content);
