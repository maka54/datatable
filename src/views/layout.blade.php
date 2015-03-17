
{{ $ajax ? '<div id="'. $id .'">' : '' }}
	
	{{ $form or '' }}

	{{ $table }}
	
	<div>
		{{ $pagination or '' }}
		{{ $elements or '' }}
		<div class="clearfix"></div>
	</div>
	

{{ $ajax ? '</div>' : '' }}

{{ $script or '' }}

