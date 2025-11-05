@include('__settings.blade.php')

{{-- @@@@@ .. @@@@@ --}}
@load ('./lang')
@load ('./js/jquery.cookie.js')
@load ('./js/layout.js')
@if ($li->icon_font == 'Y')
@load ('//use.fontawesome.com/releases/v6.6.0/css/all.css')
@endif
@mobile
	@load ('./js/layout_m.js')
	<load target="./less/layout_m.less" vars="$_theme" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no" />
@endmobile
@desktop
	<load target="./less/layout_pc.less" vars="$_theme" />
@enddesktop
{{-- @@@@@ .. @@@@@ --}}

<div @class([
	'rain-layout',
	'mf-'.$_main_font,
	'lt-'.$_layout_type,
	'st-'.$_sidebar_type,
	'is-pc' => !$im,
	'is-mobile' => $im,
	'mode-light' => $_darkmode != 'Y',
	'mode-dark' => $_darkmode == 'Y',
	'light-content' => $li->light_content == 'Y',
]) id="container">
	<div class="rl-header" role="banner">
		@mobile
			@if($_layout_type == 'N')
			<div class="rl-dome"></div><div class="rl-corner"></div><div class="rl-star rl-star--1"></div><div class="rl-star rl-star--2"></div>
			@endif
		@endmobile
		@if($_layout_type != 'N')<div class="inner">@endif
		<div class="rl-header-logo logo">
			<a href="{{ $li->site_url? $li->site_url: '/' }}">
				@if ($_site_logo)
					<img src="{{ $_site_logo }}" alt="{{ $_site_name }}" />
				@else
					{{ $_site_name }}
				@endif
			</a>
		</div>
		<div class="rl-header-bts">
			@if ($li->use_search != 'N')
				<a href="javascript:void(0)" class="bt-search" onclick="searchPop()"><span class="rlbb"><span class="rlbb-bt icon-bt"><span class="rli rli--search"></span></span><span class="rlbb-desc">@lang('cmd_search')</span></span></a>
			@endif
			@if ($li->darkmode != 'N')
				<a href="javascript:void(0)" class="bt-mode" onclick="switchMode('{{ $_COOKIE['rx_color_scheme'] }}')"><span class="rlbb"><span class="rlbb-bt icon-bt"><span @class([
					'rli',
					'rli--dark' => $_darkmode != 'Y',
					'rli--light' => $_darkmode == 'Y',
				])></span></span><span class="rlbb-desc">@if ($_darkmode == 'Y') @lang('_lightmode') @else @lang('_darkmode') @endif</span></span></a>
			@endif
			@if ($is_logged)
				<a href="@url([
					'',
					'mid' => $mid,
					'act' => 'dispMemberInfo',
					'member_srl' => $logged_info->member_srl,
				])"><span class="rlbb"><span class="rlbb-bt icon-bt"><span class="rli rli--user"></span></span><span class="rlbb-desc">@lang('member_info')</span></span></a>
			@else
				<a href="@url([
					'',
					'mid' => $mid,
					'act' => 'dispMemberLoginForm',
				])"><span class="rlbb"><span class="rlbb-bt icon-bt"><span class="rli rli--lock"></span></span><span class="rlbb-desc">@lang('cmd_login')</span></span></a>
			@endif
			@if ($im && !empty($mobile_menu->list))
				<a href="javascript:void(0)" class="bt-menu"><span class="rlbb"><span class="rlbb-bt icon-bt"><span class="rli rli--menu"></span></span><span class="rlbb-desc">@lang('_menu')</span></span></a>
			@endif
			@auth('manager')
				<a href="@url([
					'',
					'mid' => 'admin',
				])" class="bt-admin" target="_blank"><span class="rlbb"><span class="rlbb-bt icon-bt"><span class="rli rli--tool"></span></span><span class="rlbb-desc">@lang('admin')</span></span></a>
			@endauth
		</div>
		@if($_layout_type != 'N')</div>@endif
		<nav class="rl-header-gnb rl-gnb" role="navigation" aria-label="Main Navigation">
			@php $_nav_list = $gnb->list @endphp
			@includeWhen($_nav_list,'_navigation.blade.php')
		</nav>
	</div>
	@if ($li->use_search != 'N')
		<div class="rl-search">
			<form action="{{ getUrl() }}" method="post">
				<input type="hidden" name="vid" value="{{ $vid }}" />
				<input type="hidden" name="mid" value="{{ $mid }}" />
				<input type="hidden" name="act" value="IS" />
				<input type="hidden" name="search_target" value="title_content" />
				<input class="rl-search-ip" type="text" name="is_keyword" value="{{ $is_keyword }}"|cond="$is_keyword" placeholder="@lang('_insert_keyword')" />
				<div class="rl-search-bts">
					<button class="bt-cancel" type="button" onclick="closeSearch()">@lang('cmd_cancel')</button>
					<button type="submit" class="bt-submit">@lang('cmd_search')</button>
				</div>
			</form>
			<div class="click-dummy" onclick="closeSearch()"></div>
		</div>
	@endif
	@desktop
		@isset ($li->code_header_bottom) <div class="code-header-bottom">{!! $li->code_header_bottom !!}</div> @endisset
	@enddesktop
	@mobile
		@isset ($li->code_header_bottomM) <div class="code-header-bottom">{!! $li->code_header_bottomM !!}</div> @endisset
	@endmobile
	
	<div class="rl-wrapper">
		<div class="rl-content" id="content" role="main">
			@if ($_layout_type == 'R')
				{{ $content|noescape }}
			@else
				@desktop<div class="rl-dome"></div><div class="rl-corner"></div><div class="rl-star rl-star--1"></div><div class="rl-star rl-star--2"></div>@enddesktop<div class="rl-content-body">{{ $content|noescape }}</div>
			@endif
		</div>
		@desktop
			@if ($_sidebar_type != 'N')
			<aside class="rl-sb" id="sidebar">
				@isset ($li->code_sidebar) <div class="code-sidebar">{!! $li->code_sidebar !!}</div> @endisset
			</aside>
			@endif
		@enddesktop
		@mobile
			@php $_nav_list = $mobile_menu->list; @endphp
			@if ($_nav_list)
			<aside class="rl-sb" id="sidebar"><div class="click-dummy"></div><button type="button" class="bt-close"><span class="rli rli--xmark">@lang('cmd_close')</span></button><div class="inner">
				<nav class="rl-sb-menu" aria-label="Main Navigation"|cond="!$gnb->list">@include('_navigation.blade.php')</nav>
			</div></aside>
			@endif
		@endmobile
	</div>
	<div class="rl-footer">
		<div class="rl-footer-inner">
			<div class="rl-footer-menu">
				<ul>
					@if ($footer_menu->list)
						@foreach ($footer_menu->list as $key => $val)
						<li class="{{ $val['class'] }}">
							<a href="{{ $val['href'] }}" class="active"|cond="$val['selected']" target="_blank"|cond="$val['open_window']=='Y'">
								@if($val['icon']) {{ $val['icon']|noescape }} @endif
								{{ $val['text'] }}
								@if ($val['open_window']=='Y')<span class="ri ri--external-link" title="{{ $lang->new_window }}"></span>@endif
							</a>
						</li>
						@endforeach
					@endif
					@if ($is_logged)
						<li>
							<a href="@url([
								'',
								'mid' => $mid,
								'act' => 'dispMemberInfo',
								'member_srl' => $logged_info->member_srl,
							])">@lang('member_info')</a>
						</li>
						<li>
							<a href="@url([
								'',
								'mid' => $mid,
								'act' => 'dispMemberLogout',
							])">@lang('cmd_logout')</a>
						</li>
					@else
						<li>
							<a href="@url([
								'',
								'mid' => $mid,
								'act' => 'dispMemberLoginForm',
							])">@lang('cmd_login')</a>
						</li>
					@endif
				</ul>
			</div>
			<div class="rl-footer-copyright">
				Copyright(c) <a href="{{ $li->site_url? $li->site_url: '/' }}">{{ $_site_name }}</a> All Rights Reserved.
			</div>
		</div>
		<div @class([
			'rl-footer-rhymix',
			'has-code' => $li->code_footer,
		])>
			@isset ($li->code_footer)<div class="rl-footer-code code-footer"> {!! $li->code_footer !!} </div>@endisset
			<span>이 사이트는 <a href="https://rhymix.org" target="_blank">라이믹스</a>로 제작되었습니다.</span>
		</div>
	</div>
</div>