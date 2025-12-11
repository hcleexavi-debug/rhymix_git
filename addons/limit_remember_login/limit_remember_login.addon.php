<?php
// error_log('limit_remember_login.addon.php called : ' . __LINE__);
// if (!defined('RX_VERSION')) exit;
if(!defined("__XE__")) exit();
// 로그인 유지를 허용할 IP 목록
$allowed_ips = array(
    '127.0.0.1',
    '192.168.219.1',
    '59.7.27.131',
    '220.127.198.145',
    // 필요한 IP를 여기에 추가
);
// 현재 접속자의 IP
$user_ip = RX_CLIENT_IP;
// error_log("limit_remember_login.addon.php called : [" . $user_ip . "]" . __LINE__);
// 로그인 처리 시 (실제 로그인 실행 전)
if ($called_position == 'before_module_proc') {
    $act = Context::get('act');
    if ($act == 'procMemberLogin') {
        // 허용되지 않은 IP라면 로그인 유지 강제 해제
        if (!in_array($user_ip, $allowed_ips)) {
            Context::set('keep_signed', 'N');
            $_POST['keep_signed'] = 'N';
        }
    }
}
// 로그인 폼 화면에서 (체크박스 숨김)
if ($called_position == 'before_display_content') {
    $act = Context::get('act');
    if ($act == 'dispMemberLoginForm') {
        // error_log("limit_remember_login.addon.php called : [{$act}]" . __LINE__);
        // 허용되지 않은 IP라면 로그인 유지 체크박스 숨김
        if (!in_array($user_ip, $allowed_ips)) {
            // error_log("limit_remember_login.addon.php called : [{$act}]" . __LINE__);
            Context::addHtmlHeader('<style>.login-keep-button > .keep { display: none !important; }</style>');
        }
    }
}

