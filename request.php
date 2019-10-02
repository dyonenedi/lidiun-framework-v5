<?php
	/**********************************************************
	* Lidiun PHP Framework 4.0 - (http://www.lidiun.com)
	*
	* @Created in 26/08/2013
	* @Author  Dyon Enedi <dyonenedi@hotmail.com>
	* @Modify in 04/08/2014
	* @By Dyon Enedi <dyonenedi@hotmail.com>
	* @Contributor Gabriela A. Ayres Garcia <gabriela.ayres.garcia@gmail.com>
	* @Contributor Rodolfo Bulati <rbulati@gmail.com>
	* @License: free
	*
	**********************************************************/
	
	namespace Lidiun_Framework_v5;
	
	class Request
	{
		public static $_url;
		private static $_parameter;
		
		public static function init() {
			// Set URL
			$explode  = explode('/', $_SERVER['SERVER_PROTOCOL']);
			$protocol = strtolower($explode[0]);
			$protocol = $protocol.'://';

			$explode = strtolower($_SERVER ['SERVER_NAME']);
			$explode = str_replace('.', ' ', $explode);
			$explode = trim($explode);
			$explode = explode(' ',$explode);
			
			$sub = strtolower(Conf::$_conf['preset']['domain']);
			$sub = substr($sub, 0, strpos($sub, '.'));

			if (is_array($explode) && count($explode) > 1) {
				if ($explode[0] == $sub){
					header('Location: '.$protocol.'www.'.Conf::$_conf['preset']['domain']);
					exit;
				} else if (count($explode) < 3) {
					header('Location: '.$protocol.'www.'.Conf::$_conf['preset']['domain']);
					exit;
				} else {
					$subdomain = $explode[0].'.';
					unset($explode[0]);
					$domain = implode($explode, '.');
				}
			} else {
				$subdomain = '';
				$domain = $explode[0];
			}


			$uri = (!empty($_GET['uri'])) ? $_GET['uri'] : false;
			$uri = str_replace('/', ' ', $uri);
			$uri = trim($uri);
			$uri = str_replace(' ', '/', $uri);
			if ($uri) {
				$url = $protocol.$subdomain.$domain.'/'.$uri.'/';
				$uri = explode('/', $uri);
			} else {
				$url = $protocol.$subdomain.$domain.'/';
				$uri = [];
			}

			self::$_url['protocol'] = $protocol;
			self::$_url['subdomain'] = $subdomain;
			self::$_url['domain'] = $domain;
			self::$_url['site'] = $protocol.$subdomain.$domain.'/';
			self::$_url['uri'] = $uri;
			self::$_url['url'] = $url;

			self::setParameter();
		}

		#########################################################################
		######################## GLOBALS REQUEST METHODS ########################
		#########################################################################

		public static function setParameter($parameter=false){
			// Set parameters
			if (!$parameter) {
				if (!empty(self::$_url['uri']) && is_array(self::$_url['uri'])) {
					foreach (self::$_url['uri'] as $value) {
						self::$_parameter[] = $value;
					}
				}
				if (!empty($_POST) && is_array($_POST)) {
					foreach ($_POST as $key => $value) {
						self::$_parameter[$key] = $value;
					}
				}

				if (!empty($_FILES) && is_array($_FILES)) {
					foreach ($_FILES as $key => $value) {
						self::$_parameter[$key] = self::organizeArray($value);
					}
				}
			} else {
				self::$_parameter[key($parameter)] = $parameter[key($parameter)];
			}
		}

		public static function unsetParameter($key){
			unset(self::$_parameter[$key]);
		}

		public static function getParameter(){
			return self::$_parameter;
		}

		public static function organizeArray($file){
			if (is_array($file['name'])) {
				foreach ($file as $key => $value) {
					foreach ($value as $k => $val) {
						$file[$k][$key] = $val;
						unset($file[$key][$k]);
					}
					unset($file[$key]);
				}
			} else {
				$file[0] = $file;
			}

			return $file;
		}
	}