<?php namespace Maka\Datatable\Libraries;

use \HTML;
use Maka\Datatable\Datatable as Datatable;

class Column {
	
	protected $table;
	protected $name;
	protected $header;
	protected $value;
	protected $attributes;
	
	public  function __construct( Datatable $table, $name, $header = null, $value = null, $attributes = array() ){
		$this->table = $table;
		$this->name = $name;		
		$this->header = $header ?: $name;		
		$this->value = $value;		
		$this->attributes = $this->parseAttributes( $attributes );		
	}
	
	public function __get($key){
		return $this->getAttribute($key);
	}
	
	protected function getAttribute($key){
		if (isset($this->$key)){
			return $this->$key;
		}
	}

	public function __set($key, $value){
		$this->setAttribute($key, $value);
	}
	
	protected function setAttribute($key, $value){
		if (isset($this->$key)){
			$this->$key = $value;
		}
	}
	
	private function parseAttributes( $attributes ){
		
		if(!$attributes)
			return null;
		
		$e = [];
		foreach ((array) $attributes as $key => $value){
			if (is_numeric($key)) 
				$key = $value;

			if ( ! is_null($value)) 
				$e[] = "{$key}=\"{$value}\"";
		}
		
		return implode(' ', $e);
	}
		
	public function val( $e ){
		
		// si il y un callback
		if ( is_callable($this->value) ){
			return call_user_func($this->value, $e);
		} 
		
		// si il y une valeur par défaut 
		else if( $this->value ) {
			return $this->value;
		} 
		
		// sinon on recherche dans le modele
		else {
			return $e->{$this->name};
		}
		
	}
	
	public function head(){
		
		$table = $this->table;
		$class = 'sort';
		$sort = $this->name;
		
		if(!$table->sortable || (!in_array($this->name, $table->sortable->columns))) // sortable column accept not defined 
			return $this->header;
			
		list( $column, $direction) = $table->sortby();
		
		if( $column == $this->name ){
			$sort = ($direction  ? '-' : '') . $sort;
			$class .= $direction  ? ' asc' : ' desc';
		}

		if( $this->table->ajax ){
			return html_entity_decode(link_to('#' , $this->header, ['data-href' => route( $this->table->route , ['sort' => $sort ] ), 'class' => $class ]));
		}
		
		return html_entity_decode(link_to_route( $this->table->route, $this->header, ['sort' => $sort ], ['class' => $class] ));
	
	}

}