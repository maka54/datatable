<?php namespace Maka\Datatable;

use Illuminate\Config\Repository as Config;
use Illuminate\View\Factory as Factory;

class Datatable {
	
	var $config;
	var $factory;
	
	private $builder = null;
	private $length = null;
	private $columns = [];
	private $fields = null;
	private $withs = [];
	private $paginate = false;
	
	public $route = null;
	public $sortable = null;
	public $sorter = null;
	
	public function __construct(Config $config, Factory $factory) {
		$this->config = $config;
		$this->factory = $factory;	
	}
	
	public function model( $model ){
		$model = "\\{$model}";
		$this->builder = $model::select('*');
		return $this;
	}
	
	
	
	public function with(){
		$this->withs = array_merge($this->withs, func_get_args());
		return $this;
	}
	
	public function select(){
		$this->fields = func_get_args();
		return $this;
	}
	
	
	
	public function column( $column, $header = null, $value = null, $attrinute = array()){
		if(!in_array( $column, $this->columns))
			$this->columns[$column] = new Column($this, $column, $header, $value, $attrinute);
		
		return $this;
	}
	
	
	public function query( $closure ){

		if ( is_callable($closure) ){
			call_user_func($closure, $this->builder);
		}
		
		return $this;
	}
	
	public function sorter( $column ){
		preg_match('/^(-)?(.*)$/', $column, $m);
		$this->sorter = (object) ['column' => $m[2], 'direction' => empty($m[1]) ];
		return $this;
	}
	
	public function sortable( $columns, $expect = false ){
		$this->sortable = (object) ['columns' => $columns, 'expect' => $expect];
		return $this;
	}
	
	public function paginate( $length ){
		$this->length = $length;
		$this->paginate = true;
		
		return $this;
	}
	
	public function route( $route ){
		$this->route = $route;
		return $this;
	}
		
	private function build(){
		

		if( $this->fields )
			call_user_func_array(array($this->builder, 'select'), $this->fields);

		if($this->withs)
			$this->builder->with( $this->withs );
				
		$this->builder->orderBy( $this->sorter->column , ($this->sorter->direction ? 'asc' : 'desc') );
		
		if($this->paginate){
			$this->length = $this->length ?: $this->config->get('datatable::rows.default');
			return $this->builder->paginate( $this->length );
		}
			
		return $this->builder->get();
	}
	
	private function elements(){
		$elements = [];
		
		foreach($this->config->get('datatable::rows.elements') as $length){
			$elements[] = new Element( $this->route, $length, $this->length);
		}
		
		return $this->factory->make( $this->config->get('datatable::view.elements') , compact('elements'));
	}
	
	public function render( $numbers = null){
		$headers = $rows = $elements = $pagination = null;
		
		$datas = $this->build();	
		
		foreach($this->columns as $column):
			$headers[] = ['value' => $column->head(), 'attributes' => $column->attributes];
		endforeach;
		
		foreach($datas as $row):
			$cells = [];
			foreach($this->columns as $column):
				$cells[] = ['value' => $column->val( $row ), 'attributes' => $column->attributes];
			endforeach;
			$rows[] = $cells;
		endforeach;
			
			
		if($this->paginate){
			$datas->setBaseUrl( route($this->route) );
			$elements = $this->elements();
			$pagination = $datas->links();
		}


		return $this->factory->make( $this->config->get('datatable::view.table') , compact('rows', 'headers', 'pagination', 'elements' ));
	}
	
	

}