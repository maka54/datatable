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
		'layout' => 'datatable::layout',
		'table' => 'datatable::table',
		'elements' => 'datatable::elements',
		'form' => 'datatable::form',
		'pagination' => 'datatable::pagination',
	],
	
	/*
	|--------------------------------------------------------------------------
	| Prefix Session datatable uses
	|--------------------------------------------------------------------------
	| Define a prefix used to store session datatable
	|
	*/
	
	'prefix' => 'datatable.',
	
	/*
	|--------------------------------------------------------------------------
	| Directory to downloads
	|--------------------------------------------------------------------------
	| Define the directory to register export file
	|
	*/
	
	'downloads' => 'downloads',
);
