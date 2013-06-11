<?php
	###
	@ini_set("display_errors", 1);
	@ini_set("log_errors", 1);
	@ini_set("error_reporting", E_ALL);
	###
	
	### Include class Image that do image stufs" 
	require_once("../helper/image.php");
	###	
	
	### Include class Model that have everything about mysql and conect with data basic" 
	require_once("../app/model/model.php");
	###
		
	### Create a new object Systen and extends the object Helper
	class System extends Model 
	{	
		### Tags from path
		public $_root;
		public $_app;
		public $_conf;
		public $_helper;
		public $_skin;
		public $_translator;
		public $_controller;
		public $_view;
		public $_model;
		public $_css;
		public $_img;
		public $_js;
		public $_path_background;
		public $_path_favicon;
		public $_path_css_default;
		public $_path_js_default;
		public $_home;
		public $_layoutPath;
		###
		
		### Text Fix
		public $_copyright;
		###
		
		### Config
		protected $_menu;
		protected $_footer;
		protected $_layout;
		protected $_pageDefault;
		protected $_systemName;
		protected $_sessionName;
		protected $_secureCode;
		###		
		
		### Tags from domain
		protected $_exUrl;
		protected $_subdomain;
		protected $_domain;
		protected $_extDominio;
		protected $_lang;
		protected $_support;
		protected $_url;
		protected $_uri;
		protected $_debug;
		protected $_connect;
		protected $_layoutSubdomain;
		###
		
		### Tags from system
		protected $_parameter;
		protected $_page;
		protected $_session;
		protected $_content;
		protected $_menuContent;
		protected $_footerContent;
		protected $_layoutContent;
		###
		
		function __construct()
		{
			### Set Constants
			$this->setConfig();
			###
			
			### Connect data base
			$this->connect();
			###
			
			###
			session_name($this->_sessionName);
			session_set_cookie_params(0, '/', '.'.$this->_domain);
			session_start();
			ob_start();
			###
			
			###
			$this->validateSession();
			###
			
			### Get data of domain
			$this->getDomain();
			###
				
			### Mount array with parameter passed by url
			$parameter = $this->getParameter();
			###
			
			### Mount controller's Page.
			$this->mountController();
			###
			
			### Construct Menu, Footer and Layout
			$this->mountMenu();
			$this->mountFooter();
			$this->mountLayout();
			###
			
			### Mount Final Page
			$this->_layoutContent = str_replace("<%MENU%>",$this->_menuContent,$this->_layoutContent);
			$this->_layoutContent = str_replace("<%FOOTER%>",$this->_footerContent,$this->_layoutContent);
			$this->_layoutContent = str_replace("<%CONTENT%>",$this->_content,$this->_layoutContent);
			###
			
			### Traslation
			$this->translation();
			###
			
			### Show the page in the browser
			echo $this->_layoutContent;
			###
		}
		
		### Destruction
		function __destruct()
		{
			$this->disconnect();
	    }
		###
	}

	### Create a new class
	$system = new System;
	###
?>
