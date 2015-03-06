<div class="pull-right">
	<ul class="pagination">
		@foreach($elements as $element)
			@if($element->active())
				<li class="active">
					<span>{{ $element->value() }}</span>
				</li>
			@else
				<li>{{ $element->link() }}</li>
			@endif
		@endforeach

		<li class="disabled">
			<span>
				@lang('datatable::datatable.elements')
			</span>
		</li>
	</ul>
</div>