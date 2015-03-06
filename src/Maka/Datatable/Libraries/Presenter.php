<?php namespace Maka\Datatable\Libraries;

use Illuminate\Pagination\BootstrapPresenter as BootstrapPresenter;

class Presenter extends BootstrapPresenter {
	
	private $ajax = false;
	
	public function ajax(){
		$this->ajax = true;
		return $this;
	}
	
	/**
	 * Get HTML wrapper for a page link.
	 *
	 * @param  string  $url
	 * @param  int  $page
	 * @param  string  $rel
	 * @return string
	 */
	public function getPageLinkWrapper($url, $page, $rel = null)
	{
		$rel = is_null($rel) ? '' : ' rel="'.$rel.'"';
		
		if($this->ajax)
			return '<li><a href="#" data-href="'.$url.'"'.$rel.'>'.$page.'</a></li>';
		
		return parent::getPageLinkWrapper($url, $page, $rel = null);
	}


}
