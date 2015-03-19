<?php namespace Maka\Datatable;

use Illuminate\Config\Repository as Config;
use Illuminate\View\Factory as Factory;
use Illuminate\Support\Facades\Paginator as Paginator;
use Illuminate\Session\SessionManager as Session;
use Illuminate\Http\Request as Request;
use Illuminate\Support\Facades\Response as Response;

class Datatable {
	
	var $config;
	var $factory;
	
	private $builder = null;
	private $columns = [];
	private $pagination = null;
	private $paginator = null;
	public $sortable = null;
	public $searchable = null;
	public $route = null;
	public $parameters = null;
	private $session = null;
	private $SID = null;
	public $ajax = null;
	private $request = null;
	
	public function __construct(Config $config, Factory $factory, Session $session, Request $request) {
		$this->config = $config;
		$this->factory = $factory;	
		$this->session = $session;	
		$this->request = $request;	
		
		$this->storage = new Libraries\Storage( $config->get('datatable::rows.default') );
	}
	
	public function model( $model ){
		$this->builder = $model::select('*');
		$this->storage->push( $this->resolve( $model ) );
		return $this;
	}
	
	private function resolve( $model ){
		$this->SID = $SID = $this->config->get('datatable::prefix') . with(new $model)->getTable();
		return $this->session->get($SID) ?: [];
	}
	
	public function inputs( $args ){
		$this->storage->push( $args );
		return $this;
	}
	
	
	
	public function column( $column, $header = null, $value = null, $attrinute = array()){
		if(!in_array( $column, $this->columns))
			$this->columns[$column] = new Libraries\Column($this, $column, $header, $value, $attrinute);
		
		return $this;
	}
	
	public function query( $closure ){

		if ( is_callable($closure) ){
			call_user_func($closure, $this->builder);
		}
		
		return $this;
	}
	
	public function ajax(){
		$this->ajax = true;
		return $this;
	}
	
	
	public function sortable( $columns = null, $default = null){
		$this->storage->sort = $this->storage->sort ?: $default;
		
		$this->sortable = (object) ['columns' => $columns];
		return $this;
	}
	
	public function searchable( $columns = null ){
		$this->searchable = (object) ['columns' => $columns];
		return $this;
	}
	
	public function route( $route ){
		$this->route = $route;
		return $this;
	}
	
	public function sortby(){
		preg_match('/^(-)?(.*)$/', $this->storage->sort, $m);
		return [$m[2], empty($m[1]) ];
	}
		
	private function build(){
		
		$builder = $this->builder;
				
		if($this->sortable && $this->storage->sort){
			list( $column, $direction) = $this->sortby();
			$builder->orderBy( $column, $direction ? 'asc' : 'desc' );
		}
		
		if($this->storage->search){
			$colums = (array) $this->searchable->columns;
			
			$builder->where(function ($query) use ($colums) {
				$first = array_shift($colums);
				$query->where($first, 'LIKE', "%{$this->storage->search}%");
				
				foreach($colums as $column){
					$query->orWhere($column, 'LIKE', "%{$this->storage->search}%");
				}
			});
		}
		
	}

	private function built(){
		$builder = $this->builder;
		
		$collection = $builder->get();
		$count = $collection->count();
					
		$page = $this->storage->page;
		$length = $this->storage->length;
				
		$skip = ($page - 1) * $length;
		
		if($skip > $count){
			$skip = 0;
			$this->storage->page = 1;
		}
		
		$datas = $collection->slice($skip, $length);
		
		return array($datas, $count);
	}
	
	private function pagination($datas, $count){
		
		Paginator::setCurrentPage( $this->storage->page );
		$paginator = Paginator::make( (array) $datas, $count, $this->storage->length);
		$paginator->setBaseUrl( route($this->route, $this->parameters) );
		
		$presenter = new Libraries\Presenter($paginator);
		
		if( $this->ajax ){
			$presenter->ajax();
		}
		
		return $this->factory->make( $this->config->get('datatable::view.pagination') , compact('paginator', 'presenter'));
	}
	
	private function elements(){
		$elements = [];
		
		foreach($this->config->get('datatable::rows.elements') as $length){
			$elements[] = new Libraries\Element( $this->route, $length, $this->storage->length, $this->ajax);
		}
		
		return $this->factory->make( $this->config->get('datatable::view.elements') , compact('elements'));
	}
		
	private function form(){	
		$search = $this->storage->search;
		$route = $this->route;
		
		return $this->factory->make( $this->config->get('datatable::view.form') , compact('search', 'route'));
	}
	
	private function table( $datas ){

		$headers = $rows = null;

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
		
		return $this->factory->make( $this->config->get('datatable::view.table') , compact('rows', 'headers'));
	}
	
	private function script( $id ){	
		return "
		<script type='text/javascript'>
			$(function(){
				var \$datatable = $('#$id');
				
				\$datatable.on('click', '[data-href]', function(e) {
					e.preventDefault();
					href = $(this).data('href');
					
					\$datatable.addClass('loading');
					\$datatable.load( href, function() {
						\$datatable.removeClass('loading');
					});
				}).on('submit', 'form', function(e) {
					e.preventDefault();
					
					
					href = $(this).attr('action');
					fd = $(this).serialize();
					
					\$datatable.addClass('loading');
					\$datatable.load( href, fd, function() {
						\$datatable.removeClass('loading');				
					});
					
				});
										
			});
		</script>";
	}
	
	public function export(){
		
		
		
		$collection = $this->builder->get();
				
		$rows = $cells = [];
		
		foreach($this->columns as $column):
			$cells[] = $column->header;
		endforeach;
		
		$rows[] = $cells;
		
		foreach($collection as $row):
			$cells = [];
			foreach($this->columns as $column):
				$cells[] = $column->val( $row );
			endforeach;
			$rows[] = $cells;
		endforeach;
				
		$headers  = [
			'Content-type'        => 'text/csv',
			'Content-Disposition' => 'attachment; filename=products.csv'
		];
		
		$stream = function() use ($rows) {
			$output = fopen('php://output', 'w');
			foreach ($rows as $row) { 
				fputcsv($output, $row);
			}
			fclose($output);
		};
		
		return Response::stream($stream, 200, $headers);
	}
	
	public function render(){
		$script = $id = $ajax = null;
	
		// build query
		$this->build();	
		
		// return $datas built, with count
		list($datas, $count) = $this->built();	
		
		
		$pagination = $this->pagination( $datas, $count);
		$table =  $this->table( $datas );
		$elements = $this->elements();
		$form = ($this->searchable) ? $this->form() : null;

		
		// save session before rendering datatable
		$this->session->put( $this->SID, $this->storage->toArray() );
	
		
		if(!$this->request->ajax() && $this->ajax) {
			$id = str_replace('.', '_', $this->SID);
			$script = $this->script( $id ); 
			$ajax = true;
		}
		
		return $this->factory->make( $this->config->get('datatable::view.layout') , compact('table', 'form', 'elements', 'pagination', 'id', 'script', 'ajax' ));
	}
	
	
	

}
