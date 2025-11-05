@php
	/// 모바일 기기 감지
	$im = Mobile::isMobileCheckByAgent();
	/// layout preset
	$li = $layout_info;
	$_main_font = $li->main_font ?? 'nv2';
	$_layout_type = $li->layout_type ?? 'R'; // R: rain, N: rain news
	$_sidebar_type = $li->sidebar_type ?? 'N'; //L: 왼쪽, N: 사이드바 없음
	$_site_name = $li->site_name ?? 'InterTem';// Rain
	/// PC 레이아웃 가로 길이
	if (!$im):
		$_layout_type != 'N'? $_sw = 230: $_sw = 180;
		$_layout_type != 'N'? $_cw = 800: $_cw = 900;
		$_layout_type != 'N'? $_lw = 900: $_lw = 1000;
		$_layout_type != 'N'? $_size_adjust = 0: $_size_adjust = 80;
		$_size_space = 25;

		if ($_sidebar_type != 'N'):
			$_sidebar_width = $li->sidebar_width?? $_sw;
			$_layout_width_default = $_cw + $_sidebar_width;
			$_layout_width = $li->layout_width ?? $_layout_width_default;
			$_content_width = $_layout_width - ($_sidebar_width + $_size_space + $_size_adjust);
		else:
			$_layout_width = $li->layout_width ?? $_lw;
			$_content_width = $_layout_width - $_sidebar_width;
		endif;
	endif;
	/// 다크모드
	$li->darkmode == 'Y'? $_default_mode = 'dark': $_default_mode = 'light';
	if(!$_COOKIE['rx_color_scheme']) $_COOKIE['rx_color_scheme'] = $_default_mode;
	if($_COOKIE['rx_color_scheme'] == 'dark'  && $li->darkmode != 'N') $_darkmode = 'Y';
	if($_COOKIE['rx_color_scheme'] == 'light') $_darkmode = 'N';
	/// 로고
	$_darkmode != 'Y'? $_site_logo = $li->site_logo: $_site_logo = $li->site_logoD;
	/// 색상
	$_color_0 = '#000';
	$_color_3 = '#333';
	$_color_F = '#FFF';
	$_darkmode != 'Y'? $_color_star_default = '#DDD': $_color_star_default = '#555';
	$_color_star = $li->color_star?? $_color_star_default;

	$_colors = [
		'color-0' => $_color_0,
		'color-3' => $_color_3,
		'color-F' => $_color_F,
		'color-star' => $_color_star,
	];

	if ($_darkmode != 'Y'):
		$_colors_uniq = [
			'color-font' => $_color_3,
			'color-bg' => $_color_F,
		];
	else:
		$_colors_uniq = [
			'color-font' => $_color_F,
			'color-bg' => $_color_3,
		];
	endif;

	if (!$im):
		$_size = [
			'layout-width' => $_layout_width.'px',
			'sidebar-width' => $_sidebar_width.'px',
			'content-width' => $_content_width.'px',
			'size-font' => '15px',
			'size-space' => $_size_space.'px',
		];
	else:
		$_size = [
			'size-font' => '15px',
			'size-space' => '15px',
		];
	endif;

	$_theme = array_merge($_colors, $_colors_uniq, $_size);
@endphp

{{-- @@@@@ STYLE @@@@@ --}}
@mobile {{-- @@ mobile style --}}
	<style>
	</style>
@endmobile
@desktop {{-- @@ PC style --}}
	<style>
		@if ($_main_font == 'nr')
			@import url('https://cdn.jsdelivr.net/gh/innks/NanumSquareRound@master/nanumsquareround.min.css');
		@else
			@font-face {
				font-family: 'NEXON Lv2 Gothic';
				src: url('https://cdn.jsdelivr.net/gh/projectnoonnu/noonfonts_20-04@2.1/NEXON Lv2 Gothic.woff') format('woff');
				font-weight: normal;
				font-style: normal;
			}
			@font-face {
				font-family: 'NEXON Lv2 Gothic';
				src: url('https://cdn.jsdelivr.net/gh/projectnoonnu/noonfonts_20-04@2.1/NEXON Lv2 Gothic Bold.woff') format('woff');
				font-weight: bold;
				font-style: normal;
			}
		@endif
	</style>
@enddesktop
{{-- @@@@@ STYLE @@@@@ --}}
