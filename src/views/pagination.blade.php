<div class="pull-left">
	@if($paginator->getLastPage() > 1) 
		<ul class="pagination">
			{{ $presenter->render() }}
		</ul>
	@endif
</div>