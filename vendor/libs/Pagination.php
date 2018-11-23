<?php

namespace vendor\libs;

class Pagination {

	public $currentPge;
	public $perpage;
	public $total;
	public $countPages;
	public $uri;

	public function __construct($page, $perpage, $total){
		$this->perpage = $perpage;
		$this->total = $total;
		$this->countPages = $this->getCountPages();
		$this->currentPage = $this->getCurrentPage($page);
		$this->uri = '?';
	}

	public function __toString(){
		return $this->getHtml();
	}

	public function getHtml(){
		$back = null;
		$forward = null;
		$startpage = null;
		$endpage = null;
		$page2left = null;
		$page1left = null;
		$page2right = null;
		$page1right = null;

		if ($this->currentPage > 1) {
			$back = "<a href='{$this->uri}page=" . ($this->currentPage - 1) . "'>&#10094;</a>";
		}

		if ($this->currentPage < $this->countPages) {
			$forward = "<a href='{$this->uri}page=" . ($this->currentPage + 1) . "'>&#10095;</a>";

		}
		if ($this->currentPage - 2 > 0) {
			$page2left = "<a href='{$this->uri}page=" . ($this->currentPage - 2) . "'>" .($this->currentPage - 2). "</a>";
		}
		if ($this->currentPage - 1 > 0) {
			$page1left = "<a href='{$this->uri}page=" . ($this->currentPage - 1) . "'>" .($this->currentPage - 1). "</a>";
		}
		if ($this->currentPage + 1 <= $this->countPages ) {
			$page1right = "<a href='{$this->uri}page=" . ($this->currentPage + 1) . "'>" .($this->currentPage + 1). "</a>";
		}
		if ($this->currentPage + 2 <= $this->countPages ) {
			$page2right = "<a href='{$this->uri}page=" . ($this->currentPage + 2) . "'>" .($this->currentPage + 2). "</a>";
		}
		if ($this->currentPage > 3) {
			$startpage = "<a class='nav-link' href='{$this->uri}page=1'>&#10094;&#10094;</a>";
		}
		if ($this->currentPage < ($this->countPages - 2)) {
			$endpage = "<a class='nav-link' href='{$this->uri}page={$this->countPages}'>&#10095;&#10095;</a>";
		}

		return '<div id="pills-pag">' . $startpage.$back.$page2left.$page1left . 
		'<a>'.$this->currentPage.'</a>'.$page1right.$page2right.$forward.$endpage . '</div>';
	}

	public function getCountPages() {
		return ceil($this->total/$this->perpage) ?: 1;
	}

	public function getCurrentPage($page) {
		if(!$page || $page < 1){
			$page = 1;
		}
		if ($page > $this->countPages) {
			$page = $this->countPages;	
		}
		return $page;
	}

	public function getStart(){
		return ($this->currentPage - 1) * $this->perpage;
	}

}