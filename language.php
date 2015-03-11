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
	
	class Language
	{
		private static $_language;
		private static $_dictionary;

		public static function init() {
			$language = Conf::$_conf['preset']['language_default'];

			if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE']) && is_array($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
				$language = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
				if (is_array($language) && !empty($language[0])) {
					$language = strtolower(trim($language[0]));
					if (!file_exists(Path::$_path['translation'].$language.'.php')) {
						$language = Conf::$_conf['preset']['language_default'];
					}
				}
			}
			
			self::setLanguage($language);
		}

		#########################################################################
		####################### GLOBALS LANGUAGE METHODS ########################
		#########################################################################

		public static function setLanguage($language) {
			if (file_exists(Path::$_path['translation'].$language.'.php')) {
				self::$_language = $language;
				self::$_dictionary = require_once(Path::$_path['translation'].self::$_language.'.php');
			} else {
				throw new \Exception('Language to your application do not exists in translation file: ' . Path::$_path['translation'].$language.'.php');
			}
		}

		public static function getLanguage(){
			return self::$_language;
		}

		public static function translation($content=null) {
			if (isset($content)) {
				
				if (!is_array(self::$_dictionary)) {
					throw new \Exception('Translator file must be an array returned in: ' . Path::$_path['translation'].self::$_language.'.php');
				}

				foreach (self::$_dictionary as $tag => $translation) {
					$content = str_replace('<%'.$tag.'%>', $translation, $content);
				}

				return $content;
			} else {
				throw new \Exception('$content is required in Language::translation($content);');
			}
		}
	}