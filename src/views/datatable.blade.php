<div class="datatable-container">
	<table class="table table-striped table-hover datatable">
		<thead>
			<tr>
				@foreach ($headers as $head)
				<th {{ $head['attributes'] }}>{{ $head['value'] }}</th>
				@endforeach
			</tr>
		</thead>
		<tbody>
			@if( $rows )
				@foreach ($rows as $row)
				<tr>
					@foreach ($row as $cell)
					<td {{ $cell['attributes'] }}>{{ $cell['value'] }}</td>
					@endforeach
				</tr>
				@endforeach
			@endif
		</tbody>
	</table>
	
	@unless ( $rows )
		<p class="text-center">@lang('datatable::datatable.notfound')</p>
	@endunless

	@if( $pagination )
		<div>
			<div class="pull-left">
				{{ $pagination }}
			</div>
			<div class="pull-right">
				{{ $elements }}
			</div>	
			<div class="clearfix"></div>
		</div>
	@endif

</div>

