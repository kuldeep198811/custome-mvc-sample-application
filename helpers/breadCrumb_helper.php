<?php 
class BreadCrumb_helper 
{

	private $_breadCrumbs = array();
	private $_tags = array();
	private $_index = '';
	private $_output = '';
	
	public function __construct()
	{
		$this->_tags['open'] = "<ol class='breadcrumb'>";
		$this->_tags['close'] = "</ol>";
		$this->_tags['itemOpen'] = "<li>";
		$this->_tags['itemClose'] = "</li>";
	}

	public  function __add($_title, $_href)
	{		
		if (!$_title OR !$_href) return;
		$this->_breadCrumbs[] = array('title' => $_title, 'href' => $_href);
	}
	
	public  function __openTag($_tags="")
	{
		if(empty($_tags)){
			return $this->_tags['open'];
		}else{
			$this->_tags['open'] = $_tags;
		}
	}
	
	public  function __closeTag($_tags="")
	{
		if(empty($_tags)){
			return $this->_tags['close'];
		}else{
			$this->_tags['close'] = $_tags;
		}
	}
	
	public  function __itemOpenTag($_tags="")
	{
		if(empty($_tags)){
			return $this->_tags['itemOpen'];
		}else{
			$this->_tags['itemOpen'] = $_tags;
		}
	}
	
	public  function __itemCloseTag($_tags="")
	{
		if(empty($_tags)){
			return $this->_tags['itemClose'];
		}else{
			$this->_tags['itemClose'] = $_tags;
		}
	}
	
	public  function __render()
	{

		if(!empty($this->_tags['open'])){
			$this->_output = $this->_tags['open'];
		}else{
			$this->_output = '<ol class="breadcrumb">';
		}
		
		$_count = count($this->_breadCrumbs)-1;
		foreach($this->_breadCrumbs as $index => $_breadCrumb){
		
			if($this->_index == $_count){
				$this->_output .= '<li class="active">';
				$this->_output .= $_breadCrumb['title'];
				$this->_output .= '</li>';
			}else{
				$this->_output .= ($this->_tags['itemOpen'])?$this->_tags['itemOpen']:'<li>';
				$this->_output .= '<a href="'.$_breadCrumb['href'].'">';
				$this->_output .= $_breadCrumb['title'];
				$this->_output .= '</a>';
				$this->_output .= '</li>';
			}
			
		}
		
		if(!empty($this->_tags['open'])){
			$this->_output .= $this->_tags['close'];
		}else{
			$this->_output .= "</ol>";
		}		
		return $this->_output;
	}

}