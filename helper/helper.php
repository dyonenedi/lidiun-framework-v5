<?php
	### Create object that will help whit very tools (Methods)
	class Helper
	{			
		protected function setConfig()
		{
			##
			require_once("../conf/settings.php");
			##
			
			##  Set pages
			$this->_menu = "menu";
			$this->_footer = "footer";
			$this->_layout = "layout";
			##
			
			## Set parameters
			$this->_domain = $_SERVER['SERVER_NAME'];
			$exDomain = explode(".",$this->_domain);
			unset($exDomain[0]);
			$this->_domain = implode(".",$exDomain);
			$this->_public = $_SERVER["DOCUMENT_ROOT"]."/";
			$this->_root = $this->_public."../";
			$this->_support = "http://www.".$this->_domain."/support";
			###
			
			## Path
			$this->_app = $this->_root."app/";
			$this->_conf = $this->_root."conf/";
			$this->_helper = $this->_root."helper/";
			$this->_skin = $this->_public."skin/";
			$this->_translation = $this->_root."translation/";
			
			$this->_controller = $this->_app."controller/";
			$this->_view = $this->_app."view/";
			$this->_model = $this->_app."model/";
			
			$this->_css = $this->_skin."css/";
			$this->_img = $this->_skin."img/";
			$this->_js = $this->_skin."js/";
			##
			
			##
			$this->_path_background_1 = "img/bg/bg1.jpg";
			$this->_path_favicon = "img/ico/favicon.png";
			$this->_path_css_default = "css/base_1.css";
			$this->_path_js_default = "js/j_query_1.7.2.js";
			##
			
			## Link
			$this->_home = "http://www.".$this->_domain."/";
			$this->_layoutPath = "../skin/";
			##

			## Copyright
			$this->_copyright = "© ".$this->_systemName." 2013";
			##
			
			## Set connection settings
			if ( $_SERVER['SERVER_NAME'] == $this->_domaim_test)
			{
				## Base text
				$this->db_user = $this->db_user_test;
				$this->db_host = $this->db_host_test;
				$this->db_password   = $this->db_password_test;
				$this->db_name = $this->db_name_test;
				##
			}else{
				## Base production
				$this->db_user = $this->db_user_production;
				$this->db_host = $this->db_host_production;
				$this->db_password   = $this->db_password_production;
				$this->db_name = $this->db_name_production;
				##
			}
			##
		}
		
		protected function support()
		{
			if ($this->_debug == false) {
				header("Location: ".$this->_support);
			}
		} 
		
		## Connect in the data base
		protected function connect()
		{
			if ($this->_connect) {
				$this->_connection = @mysqli_connect( $this->db_host, $this->db_user, $this->db_password, $this->db_name);
				if ($this->_connection == false) {
					$this->support();
					echo mysqli_connect_error();
					exit;
				}
			}
		}
		##
		
		## Disconnect from data base
		protected function disconnect()
		{
			if ($this->_connect) {
				@mysqli_close($this->connection);
			}
		}
		##
		
		protected function getDomain()
		{
			$this->_exUrl = explode('.',$_SERVER["HTTP_HOST"]);
			$this->_subdomain = $this->_exUrl[0];
			$this->_domain = $this->_exUrl[1];
			$this->_extDominio = isset($this->_exUrl[3]) ? $this->_exUrl[2].'.'.$this->_exUrl[3] : $this->_exUrl[2] ;
			$this->_uri = $this->_subdomain.".".$this->_domain.".".$this->_extDominio;
			$this->_url = $this->_uri.$_SERVER["REQUEST_URI"];
			$this->_lang = (isset($this->_subdomain)  && $this->_subdomain != "www") ? strtolower($this->_subdomain) : "en-us" ;
		}
		
		protected function getParameter()
		{
			$this->_parameter = explode("/",$_SERVER ["REQUEST_URI"]);
			unset($this->_parameter[0]);
			
			if (isset($this->_parameter[1]) && $this->_parameter[1] != "") {
				$this->_page = $this->_parameter[1];
			} else {
				$this->_page = $this->_pageDefault;
			}
			unset($this->_parameter[1]);
			
			for ($i = 0; $i < count($this->_parameter); $i++) {
				$this->_parameter[$i] = $this->_parameter[$i+2];
			}
		}
		
		protected function validateSession()
		{
			if (isset($_SESSION["SECURY_CODE"]) && $_SESSION["SECURY_CODE"] === $this->_secureCode) {
				$this->session = true;
			} else {
				$this->session = true;
			}
		}
		
		protected function mountController()
		{
			if (file_exists($this->_controller.$this->_page.".php")) {
				require_once($this->_controller.$this->_page.".php");
			} else {
				$this->support();
			}
		}
		
		protected function mountMenu()
		{
			if (file_exists($this->_view.$this->_menu.".html")) {
				$handle = fopen($this->_view.$this->_menu.".html","r");
				$content = fread($handle,filesize($this->_view.$this->_menu.".html"));
				fclose($handle);
				$content = str_replace("<%LAYOUT_TAG%>",$this->_layoutPath,$content);
				
				$this->_menuContent = $content;
			} else {
				$this->support();
			}
		}
		
		protected function mountFooter()
		{
			if (file_exists($this->_view.$this->_footer.".html")) {
				$handle = fopen($this->_view.$this->_footer.".html","r");
				$content = fread($handle,filesize($this->_view.$this->_footer.".html"));
				fclose($handle);
				$content = str_replace("<%COPYRIGHT_TAG%>",$this->_copyright,$content);
				
				$this->_footerContent = $content;
			} else {
				$this->support();
			}
		}
		
		protected function mountLayout()
		{
			if (file_exists($this->_view.$this->_layout.".html")) {
				$handle = fopen($this->_view.$this->_layout.".html","r");
				$content = fread($handle,filesize($this->_view.$this->_layout.".html"));
				fclose($handle);
				$content = str_replace("<%FAVICON_TAG%>",$this->_path_favicon,$content);
				$content = str_replace("<%SYSTEM_NAME_TAG%>",$this->_systemName,$content);
				$content = str_replace("<%CSS_DEFAULT_TAG%>",$this->_path_css_default,$content);
				$content = str_replace("<%JS_DEFAULT_TAG%>",$this->_path_js_default,$content);
				$content = str_replace("<%BACKGROUND_TAG%>",$this->_path_background,$content);
				$content = str_replace("<%LAYOUT_TAG%>",$this->_layoutPath,$content);
				$content = str_replace("<%TITLE%>",ucwords($this->_page),$content);

				$this->_layoutContent = $content; 
			} else {
				$this->support();
			}
		}
		
		protected function mountHtml($page)
		{
			if (file_exists($this->_view.$page.".html")) {
				$handle = fopen($this->_view.$page.".html","r");
				$content = fread($handle,filesize($this->_view.$page.".html"));
				fclose($handle);

				return $content; 
			} else {
				$this->support();
			}
		}
		
		protected function translation()
		{
			if (file_exists($this->_translation.$this->_lang.".php")) {
				require_once($this->_translation.$this->_lang.".php");
				foreach ($idiomas as $key => $valor) {
					$this->_layoutContent = str_replace('<%'.$key.'%>',$valor,$this->_layoutContent);
				}
			} else {
				$this->support();
			}
		}
		
		###############################################################
		###-------------------- Your Methods ----------------------###
		###############################################################
		
		## Validate name of e-mail
		protected function validateEmail($email){
			#
			if(preg_match("/^([[:alnum:]_.-]){3,}@([[:lower:][:digit:]_.-]{3,})(\.[[:lower:]]{2,3})(\.[[:lower:]]{2})?$/", $email)) {
				return true;
			}else{
				return false;
			}
			#
		}
		##
		
		## Download file
		protected function download($filePath,$fileName)
		{		
			## Define limit time 0 to low connections
			set_time_limit(0);
			##
			
			if (!file_exists($filePath)) {
				$this->support();
			} else {
				##
				header('Content-Description: File Transfer');
				header('Content-Disposition: attachment; filename='.$fileName.' ');
				header('Content-Type: application/octet-stream');
				header('Content-Transfer-Encoding: binary');
				header('Content-Length: ' . filesize($filePath));
				header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
				header('Pragma: public');
				header('Expires: 0');
				##
				
				## Send file to client
				readfile($filePath);
				exit();
				##
			}
		}
		##
		
		###############################################################
		###------------------- End of Methods ---------------------###
		###############################################################
	}
	###
?>
