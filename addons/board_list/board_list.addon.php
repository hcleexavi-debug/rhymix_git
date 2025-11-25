<?php
if(!defined("__XE__")) exit();

// 레이아웃 컴파일 전에 실행되는 시점 사용
if($called_position != 'before_module_proc') return;

// 현재 mid 확인 - 메인페이지는 'index'
$current_mid = Context::get('mid');

// 메인페이지가 아니면 종료
if($current_mid != 'index') return;

// CSS 파일 추가 (최신 방식)
Context::loadFile(array('./addons/board_list/board_list_addon.css', '', '', 100), true);

// 로그인 정보 가져오기
$logged_info = Context::get('logged_info');

// 모듈 모델 로드
$oModuleModel = getModel('module');
$oDocumentModel = getModel('document');

// 게시판 모듈 목록 가져오기
$args = new stdClass();
$args->module = 'board';
$output = executeQueryArray('module.getMidList', $args);

$board_list_html = '';

if($output->data && count($output->data) > 0) {
    $board_list_html .= '<div class="board-list-addon">';
    $board_list_html .= '<h2 class="board-list-title">게시판 목록</h2>';
    $board_list_html .= '<ul class="allowed-board-list">';

    $is_access = false;
    foreach($output->data as $module_info) {
        // 각 게시판의 상세 정보 가져오기
        $board_info = $oModuleModel->getModuleInfoByMid($module_info->mid);

        if($board_info) {
            // 권한 체크 - 목록보기 권한 확인
            $grant = $oModuleModel->getGrant($board_info, $logged_info);
            
            // 목록보기 권한이 있는 경우만 표시
            if($grant->access) {
                $is_access = true;
                $browser_title = htmlspecialchars($board_info->browser_title ?: $board_info->mid);
                $description = htmlspecialchars($board_info->description ?: '');
                $url = getUrl('', 'mid', $board_info->mid);
                
                $board_list_html .= '<li class="board-item">';
                $board_list_html .= '<div class="board-header">';
                $board_list_html .= '<strong class="board-title"><a href="'.$url.'">'.$browser_title.'</a></strong>';
                if($description) {
                    $board_list_html .= '<p class="board-desc">'.$description.'</p>';
                }
                $board_list_html .= '</div>';
                
                // 카테고리(분류) 정보 가져오기 - Rhymix 2.1 방식
                $category_list = $oDocumentModel->getCategoryList($board_info->module_srl);
                
                if($category_list && count($category_list) > 0) {
                    $board_list_html .= '<div class="board-categories">';
                    $board_list_html .= '<span class="category-label">분류:</span>';
                    $board_list_html .= '<ul class="category-list">';
                    
                    foreach($category_list as $category) {
                        if($category->title) {
                            $category_url = getUrl('', 'mid', $board_info->mid, 'category', $category->category_srl);
                            $board_list_html .= '<li><a href="'.$category_url.'">'.htmlspecialchars($category->title).'</a></li>';
                        }
                    }
                    
                    $board_list_html .= '</ul>';
                    $board_list_html .= '</div>';
                }
                
                $board_list_html .= '</li>';
            }
        }
    }

    if(!$is_access) {
        $board_list_html .= '<li class="no-access">접근 가능한 게시판이 없습니다.</li>';
    }
    
    $board_list_html .= '</ul>';
    $board_list_html .= '</div>';
    
    
}

// Context에 HTML 저장
Context::set('board_list_html', $board_list_html);