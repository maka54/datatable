<?php
return array(

	/*
	|--------------------------------------------------------------------------
	| Number Elements show per page
	|--------------------------------------------------------------------------
	|
	| This option controls numbers elements to show per page
	|
	*/
	'rows' => [
		'elements' => ['10', '30', '50', '100'],
		'default' => 30,
	],
	
	/*
	|--------------------------------------------------------------------------
	| View datatable uses
	|--------------------------------------------------------------------------
	|
	|
	*/
	
	'view' =>  [
		'table' => 'datatable::datatable',
		'elements' => 'datatable::elements',
	],
);