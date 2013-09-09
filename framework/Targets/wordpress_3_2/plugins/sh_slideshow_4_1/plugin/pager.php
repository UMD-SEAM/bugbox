<?php
class page{
	var $pagesize = 20; // How many in one page
	var $linksize = 10; // How many links display
	var $sql; // SQL query
	var $pages; // Total Pages
	var $link_pages; // Total Link Pages
	var $current_page; // Current Page
	var $start; // Start row in database table
	var $current_link_page; // Current Link Page
	var $url; // URL display in address bar
	var $next_page = 'Next';
	var $pre_page = 'Pre';
	var $first_page = '&laquo;';
	var $last_page = '&raquo;';
	
	// Initial
	function init(){
		global $wpdb;
		$rows = count($wpdb->get_results($this->sql));//numbers of jobs
		$this->pages= ceil($rows/$this->pagesize);//calculate pages
		$this->link_pages = ceil($this->pages/$this->linksize);//calculate link pages
		// Current page
		if(isset($_GET['pg_no'])):
			$this->current_page = $_GET['pg_no'];
		else:
			$this->current_page = 1;
		endif;
		$this->url = $_SERVER['REQUEST_URI'];
		
		$this->start = ($this->current_page*$this->pagesize)-$this->pagesize;
	}
	// Display Lists
	function show_pager(){
		// Current link page
		$this->current_link_page = ceil($this->current_page/$this->linksize);
		$end_link = ($this->current_link_page*$this->linksize);
		$start_link = ($end_link-$this->linksize)+1;
		if($end_link > $this->pages):
			$end_link = $this->pages;
		endif;
		// Display
		if($this->pages >1):
			echo '<ul class="sh_slideshow_nav">';
			if($this->current_page > 1):
				echo '<li class="first_page"><a href="'.$this->get_page_link(1).'" title="">'.$this->first_page.'</a></li>';
				echo '<li class="pre_page"><a href="'.$this->get_page_link($this->current_page-1).'" title="">'.$this->pre_page.'</a></li>';
			endif;
			for($i=$start_link;$i<=$end_link;$i++):
				if($i == $this->current_page):
					echo '<li class="current_page">'.$i.'</li>';
				else:
					echo '<li><a href="'.$this->get_page_link($i).'" title="">'.$i.'</a></li>';
				endif;
			endfor;
			if($this->current_page < $this->pages):
				echo '<li class="next_page"><a href="'.$this->get_page_link($this->current_page+1).'" title="">'.$this->next_page.'</a></li>';
				echo '<li class="last_page"><a href="'.$this->get_page_link($this->pages).'" title="">'.$this->last_page.'</a></li>';
			endif;
			echo '</ul>';
		endif;
	}
	// Generate url
	function get_page_link($index){
		if(empty($_SERVER['QUERY_STRING'])):// No query string
			$url = $this->url.'?pg_no='.$index;
		elseif(stristr($_SERVER['QUERY_STRING'],'pg_no=')):
			$url = str_replace('pg_no='.$this->current_page,'pg_no='.$index,$this->url);
		else:
			$url = $this->url.'&pg_no='.$index;
		endif;
		return $url;
	}
}
?>