
<div class="clearfix">
	{{ Form::open(array('url' => $url, 'method' => 'GET', 'class' => 'form-horizontal col-lg-3' )) }}
	<div class="form-group">
		<div class="input-group">
			{{ Form::text('search', $search, ['class' => 'form-control', 'placeholder' => Lang::get('datatable::datatable.form.placeholder')]) }}
			<div class="input-group-btn">
				{{ Form::submit( Lang::get('datatable::datatable.form.submit') , ['class' => 'btn btn-default']) }}
				{{ Form::submit( Lang::get('datatable::datatable.form.reset') , ['class' => 'btn btn-link', 'name' => 'reset']) }}
			</div>
		</div>
	</div>
	{{ Form::close() }}
</div>
