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
	
	class Path
	{
		public static $_path;
		
		public static function init() {
			// Set paths to your application
			self::$_path['app'] = '../';

			self::$_path['api'] = self::$_path['app'].'api/';
			self::$_path['conf'] = self::$_path['app'].'conf/';
			self::$_path['plugin'] = self::$_path['app'].'plugin/';
			self::$_path['public'] = '';
			self::$_path['repository'] = self::$_path['app'].'repository/';
			self::$_path['translation'] = self::$_path['app'].'translation/';

			if (!empty(Conf::$_conf['path']['public_path']) && is_array(Conf::$_conf['path']['public_path'])) {
				foreach (Conf::$_conf['path']['public_path'] as $key => $value) {
					$value = str_replace('/', ' ', $value);
					$value = trim($value);
					$value = str_replace(' ', '/', $value);
					
					self::$_path[$key] = self::$_path['public'].$value.'/';
				}
			}
			
			self::$_path['ajax'] = self::$_path['repository'].'ajax/';
			self::$_path['mobile'] = self::$_path['repository'].'mobile/';
			self::$_path['layout'] = self::$_path['repository'].'layout/';
			self::$_path['render'] = self::$_path['repository'].'render/';
			
			self::$_path['web'] = self::$_path['ajax'].'web/';
			self::$_path['mobile'] = self::$_path['ajax'].'mobile/';
			
			self::$_path['content'] = self::$_path['layout'].'content/';
			self::$_path['segment'] = self::$_path['layout'].'segment/';
		}
	}