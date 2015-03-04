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
	
	class Link
	{
		public static $_link;

		public static function init() {
			// Set links to your application
			self::$_link['public'] = '/';

			if (!empty(Conf::$_conf['path']['public_path']) && is_array(Conf::$_conf['path']['public_path'])) {
				foreach (Conf::$_conf['path']['public_path'] as $key => $value) {
					$value = str_replace('/', ' ', $value);
					$value = trim($value);
					$value = str_replace(' ', '/', $value);
					
					self::$_link[$key] = self::$_link['public'].$value.'/';
				}
			}
		}
	}