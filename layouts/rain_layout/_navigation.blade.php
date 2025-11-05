
<ul class="menu-depth1">
	@foreach ($_nav_list as $key => $val)@isset ($val['href'])
	<li class="{{ $val['class'] }}">
		<a href="{{ $val['href'] }}" class="active"|cond="$val['selected']" target="_blank"|cond="$val['open_window']=='Y'">
			@if($val['icon']) {{ $val['icon']|noescape }} @endif
			{{ $val['link']|noescape }}
			@if ($val['open_window']=='Y')<span class="rli rli--external-link" title="{{ $lang->new_window }}"></span>@endif
		</a>
		@if (!empty($val['list']))
		<ul class="menu-depth2">
			@foreach ($val['list'] as $key2 => $val2)@isset ($val2['href'])
			<li class="{{ $val2['class'] }}">
				<a href="{{ $val2['href'] }}" class="active"|cond="$val2['selected']" target="_blank"|cond="$val2['open_window']=='Y'">
					@if($val2['icon']) {{ $val2['icon']|noescape }} @endif
					{{ $val2['text'] }}
					@if ($val2['open_window']=='Y')<span class="rli rli--external-link" title="{{ $lang->new_window }}"></span>@endif
				</a>
			</li>
			@endisset@endforeach
		</ul>
		@endif
	</li>
	@endisset@endforeach
</ul>