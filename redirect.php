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
	
	Class Redirect
	{
		public static $_message = '';
		public static $_page    = '';

		public static function content($contet){
			self::$_page    = $contet;
			self::$_message = 'Content not found';
			Render::setRender("Lidiun\Redirecting");
		}

		public static function notFound($message=false){
			self::$_page    = 'not_found';
			self::$_message = (!empty($message)) ? $message : 'Page not found';
			Render::setRender("Lidiun\Redirecting");
		}

		public static function support($message=false){
			self::$_page    = 'support';
			self::$_message = (!empty($message)) ? $message : 'This site is off line right now';
			Render::setRender("Lidiun\Redirecting");
		}

		public static function supportIe($message=false){
			self::$_page    = 'support_ie';
			self::$_message = (!empty($message)) ? $message : 'This site is not supported in this browse';
			Render::setRender("Lidiun\Redirecting");
		}

		public static function to($to){
			header('Location: '.$to);
			exit;
		}
	}