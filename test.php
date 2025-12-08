<?php
// 의도적으로 존재하지 않는 함수 호출
opcache_reset();
echo "opcache reset <br/>";

$path = __DIR__ . '/modules/member/view/custom/member.view.php'; // 경로 조정
if (file_exists($path)) {
    echo "exists\n";
    echo file_get_contents($path);
} else {
    echo "NOT FOUND: $path\n";
}