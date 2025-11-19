<?php
if(!defined("__XE__")) exit();

// 메인페이지의 본문 위에만 표시
if($called_position != 'before_display_content') return;

// 현재 mid 확인 - 메인페이지는 'index'
$current_mid = Context::get('mid');

// 메인페이지가 아니면 종료
if($current_mid != 'index') return;
echo "#12<br>";
// 로그인 정보 가져오기
$logged_info = Context::get('logged_info');

// 모듈 모델 로드
$oModuleModel = getModel('module');

// 게시판 모듈 목록 가져오기
$args = new stdClass();
$args->module = 'board';
$output = executeQueryArray('module.getMidList', $args);

$board_list_html = '';
echo "#25<br>";
if($output->data && count($output->data) > 0) {
    $board_list_html .= '<div class="board-list-addon">';
    $board_list_html .= '<h2 class="board-list-title">게시판 목록</h2>';
    $board_list_html .= '<ul class="allowed-board-list">';
    
    foreach($output->data as $module_info) {
        // 각 게시판의 상세 정보 가져오기
        $board_info = $oModuleModel->getModuleInfoByMid($module_info->mid);
        
        if($board_info) {
            // 권한 체크 - 목록보기 권한 확인
            $grant = $oModuleModel->getGrant($board_info, $logged_info);
            
            // 목록보기 권한이 있는 경우만 표시
            if($grant->access) {
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
                
                // 카테고리(분류) 정보 가져오기
                if($board_info->use_category == 'Y' && $board_info->category_list) {
                    $categories = explode(',', $board_info->category_list);
                    
                    if(count($categories) > 0) {
                        $board_list_html .= '<div class="board-categories">';
                        $board_list_html .= '<span class="category-label">분류:</span>';
                        $board_list_html .= '<ul class="category-list">';
                        
                        foreach($categories as $category) {
                            $category = trim($category);
                            if($category) {
                                $category_url = getUrl('', 'mid', $board_info->mid, 'category', $category);
                                $board_list_html .= '<li><a href="'.$category_url.'">'.htmlspecialchars($category).'</a></li>';
                            }
                        }
                        
                        $board_list_html .= '</ul>';
                        $board_list_html .= '</div>';
                    }
                }
                
                $board_list_html .= '</li>';
            }
        }
    }
    
    $board_list_html .= '</ul>';
    $board_list_html .= '</div>';
    
    // CSS 스타일 추가
    $board_list_html .= '
    <style>
    .board-list-addon {
        margin: 20px 0 30px 0;
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
    }
    
    .board-list-addon .board-list-title {
        margin: 0 0 20px 0;
        padding-bottom: 10px;
        font-size: 20px;
        font-weight: bold;
        color: #333;
        border-bottom: 2px solid #0066cc;
    }
    
    .board-list-addon .allowed-board-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .board-list-addon .board-item {
        padding: 15px;
        margin-bottom: 15px;
        background-color: #ffffff;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        transition: all 0.3s ease;
    }
    
    .board-list-addon .board-item:last-child {
        margin-bottom: 0;
    }
    
    .board-list-addon .board-item:hover {
        background-color: #f0f7ff;
        border-color: #0066cc;
        box-shadow: 0 2px 8px rgba(0, 102, 204, 0.1);
    }
    
    .board-list-addon .board-header {
        margin-bottom: 10px;
    }
    
    .board-list-addon .board-title a {
        color: #333;
        text-decoration: none;
        font-size: 18px;
        font-weight: bold;
    }
    
    .board-list-addon .board-title a:hover {
        color: #0066cc;
    }
    
    .board-list-addon .board-desc {
        margin: 8px 0 0 0;
        color: #666;
        font-size: 14px;
        line-height: 1.5;
    }
    
    .board-list-addon .board-categories {
        padding-top: 10px;
        border-top: 1px solid #f0f0f0;
    }
    
    .board-list-addon .category-label {
        display: inline-block;
        margin-right: 10px;
        font-size: 13px;
        font-weight: bold;
        color: #666;
    }
    
    .board-list-addon .category-list {
        display: inline-flex;
        flex-wrap: wrap;
        gap: 6px;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .board-list-addon .category-list li {
        display: inline-block;
    }
    
    .board-list-addon .category-list a {
        display: inline-block;
        padding: 4px 12px;
        background-color: #e8f4ff;
        color: #0066cc;
        text-decoration: none;
        font-size: 13px;
        border-radius: 4px;
        transition: all 0.2s ease;
    }
    
    .board-list-addon .category-list a:hover {
        background-color: #0066cc;
        color: #ffffff;
    }
    
    /* 반응형 디자인 */
    @media (max-width: 768px) {
        .board-list-addon {
            padding: 15px;
            margin: 15px 0 20px 0;
        }
        
        .board-list-addon .board-list-title {
            font-size: 18px;
        }
        
        .board-list-addon .board-item {
            padding: 12px;
        }
        
        .board-list-addon .board-title a {
            font-size: 16px;
        }
        
        .board-list-addon .category-list {
            margin-top: 5px;
        }
    }
    </style>
    ';
} else {
    // 접근 가능한 게시판이 없는 경우
    if($logged_info) {
        $board_list_html .= '<div class="board-list-addon">';
        $board_list_html .= '<p class="no-board-message">접근 가능한 게시판이 없습니다.</p>';
        $board_list_html .= '</div>';
    }
}

echo "<pre>{$board_list_html}</pre>";
// Context에 HTML 저장
Context::set('board_list_html', $board_list_html);