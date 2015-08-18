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
	
	namespace Lidiun;

	class Render
	{
		private static $_ajax;
		private static $_web;
		private static $_mobile;
		private static $_render;
		private static $_reply;
		private static $_entity;

		public static function init() {
			// Set render
			$parameter = Request::getParameter();

			if (empty($parameter)) {
				$parameter[0] = Conf::$_conf['preset']['default_render'];
			}

			if (!empty($parameter[0]) && empty($parameter['render'])) {
				/**
				* If is url request.
				*
				*/
				
				if (file_exists(Path::$_path['render'].$parameter[0].'.php')) {
					// If exists a render with first parameter
					self::$_render = strtolower($parameter[0]);
					Request::unsetParameter(0);
				} else if (!empty($parameter[1]) && file_exists(Path::$_path['render'].$parameter[1].'.php')) {
					// If exists a render with second parameter	
					self::$_entity = strtolower($parameter[0]);
					self::$_render = strtolower($parameter[1]);
					Request::unsetParameter(0);
					Request::unsetParameter(1);
				} else if (file_exists(Path::$_path['content'].$parameter[0].'.html')) {
					// If exists a countent with first parameter	
					Redirect::content($parameter[0]);
				} else {
					Redirect::notFound();
				}
			} else if (!empty($parameter[0]) && !empty($parameter['render'])) {
				/**
				* If is ajax request and have a render
				*
				*/
				if ($parameter[0] == 'web') {
					self::$_ajax = true;
					self::$_web = true;
					self::$_render = strtolower($parameter['render']);
					Request::unsetParameter(0);
					Request::unsetParameter('render');

					if (!file_exists(Path::$_path['web'].self::$_render.'.php')) {
						Redirect::notFound();
					}
				} else if ($parameter[0] == 'mobile') {
					self::$_ajax = true;
					self::$_mobile = true;
					self::$_render = strtolower($parameter['render']);
					Request::unsetParameter(0);
					Request::unsetParameter('render');

					if (!file_exists(Path::$_path['mobile'].self::$_render.'.php')) {
						Redirect::notFound();
					}
				} else {
					Redirect::notFound();
				}
			}
			
			if (!empty(self::$_web)) {
				Autoload::includePath('repository/ajax/web');
			} else if (!empty(self::$_mobile)) {
				Autoload::includePath('repository/ajax/mobile');
			} else {
				Autoload::includePath('repository/render');
				Layout::ContentAutoload(self::$_render);
			}

			if (empty(self::$_web) && empty(self::$_mobile)) {
				Layout::putLayout(Layout::getSegment('system/layout'));
			}
			
			self::initGlobalRender();
			
			self::runRender();

			self::finish();
		}

		##########################################################################
		######################## PRIVATE RENDER METHODS ##########################
		##########################################################################

		private static function initGlobalRender() {
			if (file_exists(Path::$_path['render'].'global_render.php')) {
				include_once(Path::$_path['render'].'global_render.php');
				$globalRender = 'Global_render';
				$globalRender = new $globalRender;
				$globalRender = null;
			}
		}

		private static function runRender() {
			$render = Render::getRender();
			$render = new $render;
			$render = null;
		}

		private static function finish() {
			// Set request
			if (!empty(self::$_web)) {
				if (!empty(self::$_reply) && is_array(self::$_reply)) {
					foreach (self::$_reply as $key => $value) {
						if ($key == 'reply') {
							self::$_reply[$key] = $value;
						} else if (is_numeric($value)) {
							self::$_reply[$key] = $value;
						} else {
							self::$_reply[$key] = Language::translation($value);
						}
					}
				} else {
					self::$_reply = ['reply' => false, 'message' => 'No reply seted in the web render'];
				}
			} else if (!empty(self::$_mobile)) {
				if (!empty(self::$_reply) && is_array(self::$_reply)) {
					foreach (self::$_reply as $key => $value) {
						if ($key == 'reply') {
							self::$_reply[$key] = $value;
						} else if (is_numeric($value)) {
							self::$_reply[$key] = $value;
						} else {
							self::$_reply[$key] = ($value) ? Language::translation($value) : $value;
						}
					}
				} else {
					self::$_reply = [ 'reply' => false, 'message' => 'No reply seted in the mobile render'];
				}
			} else {
				Layout::MenuFooterAutoload();
				Layout::replaceTag();
				Layout::translation();
			}

			// Close conection with Data Base;
			Database::close();	
			
			// deliver layout 
			if (!empty(self::$_web)) {
				header('Content-Type: application/json; charset=UTF-8');
				header('Access-Control-Allow-Methods: GET, POST');
				echo json_encode(self::$_reply);
			} else if (self::$_mobile) {
				header('Content-Type: application/json; charset=UTF-8');
				header('Access-Control-Allow-Methods: GET, POST');
				header('Access-Control-Allow-Headers: *');
				header('Access-Control-Allow-Origin: *');
				echo json_encode(self::$_reply);
			} else {
				header('Content-Type: text/html; charset=UTF-8');
				header('Access-Control-Allow-Methods: GET, POST');
				header('Access-Control-Allow-Headers: *');
				header("Access-Control-Allow-Origin: https://sandbox.pagseguro.uol.com.br");
				
				echo Layout::getLayout();
			}
		}

		##########################################################################
		######################## GLOBAL RENDER METHODS ###########################
		##########################################################################

		public static function setRender($render){
			self::$_render = $render;
		}

		public static function getAjax(){
			return self::$_ajax;
		}

		public static function getEntity(){
			return self::$_entity;
		}

		public static function getRender(){
			return self::$_render;
		}

		public static function setReply(array $reply){
			self::$_reply = $reply;
		}

		public static function setDisplayError($debug=null) {
			$debug = (isset($debug)) ? $debug : Conf::$_conf['preset']['debug'];
			if ($debug) {
			    error_reporting(E_ALL);
			} else {
				error_reporting(0);
			}
		}

		public static function setDenyIe($bool=true) {
			if ($bool) {
				preg_match('/MSIE (.*?);/', $_SERVER['HTTP_USER_AGENT'], $matches);
				if (count($matches) < 2) {
					preg_match('/Trident\/\d{1,2}.\d{1,2}; rv:([0-9]*)/', $_SERVER['HTTP_USER_AGENT'], $matches);
				}

				if (count($matches) > 1){
					$version = $matches[1];

					switch(true){
						case ($version <= 8):
						Redirect::supportIe('This site is not supported in this browse (IE 8 or after)');
						break;

						case ($version == 9 || $version == 10):
						Redirect::supportIe('This site is not supported in this browse (IE9 & IE10)');
						break;

						case ($version == 11):
						Redirect::supportIe('This site is not supported in this browse (IE10)');
						break;

						default:
						Redirect::supportIe();
					}
				}
			}
		}
	}