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
	
	class Layout
	{
		private static $_layout;
		private static $_content;
		private static $_menu;
		private static $_footer;
		private static $_renderMenu;
		private static $_renderFooter;
		private static $_title;
		private static $_description;

		public static function setTitle($title) {
			self::$_title = $title;
		}

		public static function setDescription($description) {
			self::$_description = $description;
		}

		public static function ContentAutoload($content) {
			if (file_exists(Path::$_path['content'].$content.'.html')) {
				self::$_content = file_get_contents(Path::$_path['content'].$content.'.html');
			}
		}

		public static function MenuFooterAutoload() {
			if (!empty(self::$_renderMenu)) {
				if (empty(self::$_menu)) {
					if (file_exists(Path::$_path['segment'].'system/menu.html')) {
						self::$_menu = file_get_contents(Path::$_path['segment'].'system/menu.html');
					} else {
						throw new \Exception('I can\'t autoload menu.html with: "Layout::layoutAutoload()" File: "'.Path::$_path['segment'].'system/menu.html" do not exists');
					}
				}
			} else {
				self::$_menu = '';
			}

			if (!empty(self::$_renderFooter)) {
				if (empty(self::$_footer)) {
					if (file_exists(Path::$_path['segment'].'system/footer.html')) {
						self::$_footer = file_get_contents(Path::$_path['segment'].'system/footer.html');
					} else {
						throw new \Exception('I can\'t autoload footer.html with: "Layout::layoutAutoload()" File: "'.Path::$_path['segment'].'system/footer.html" do not exists');
					}
				}
			} else {
				self::$_footer = '';
			}
		}

		public static function replaceTag() {
			$content = (!empty(self::$_content)) ? self::$_content : '';
			$menu = (!empty(self::$_menu)) ? self::$_menu : '';
			$footer = (!empty(self::$_footer)) ? self::$_footer : '';

			$commonCss = '';
			$commonJs = '';

			if (!empty(Conf::$_conf['path']['common_css']) && is_array(Conf::$_conf['path']['common_css'])) {
				foreach (Conf::$_conf['path']['common_css'] as $key => $value) {
					$commonCss .= '<link href="'.Link::$_link['css'].$value.'" rel="stylesheet" type="text/css"/>';
				}
			}

			if (!empty(Conf::$_conf['path']['common_js']) && is_array(Conf::$_conf['path']['common_js'])) {
				foreach (Conf::$_conf['path']['common_js'] as $key => $value) {
					$commonJs .= '<script src="'.Link::$_link['js'].$value.'" type="text/javascript"></script>';
				}
			}

			$additionalCss = '';
			$additionalJs = '';

			if (!empty(Conf::$_conf['path']['additional_css']) && is_array(Conf::$_conf['path']['additional_css'])) {
				foreach (Conf::$_conf['path']['additional_css'] as $key => $value) {
					$additionalCss .= '<link href="'.Link::$_link['css'].$value.'" rel="stylesheet" type="text/css"/>';
				}
			}

			if (!empty(Conf::$_conf['path']['additional_js']) && is_array(Conf::$_conf['path']['additional_js'])) {
				foreach (Conf::$_conf['path']['additional_js'] as $key => $value) {
					$additionalJs .= '<script src="'.Link::$_link['js'].$value.'" type="text/javascript"></script>';
				}
			}

			$title = (!empty(self::$_title)) ? self::$_title: ucwords(Render::getRender());
			$description = (!empty(self::$_description)) ? self::$_description: Conf::$_conf['preset']['description'];

			$layout = self::$_layout;
			$layout = str_replace('<%CONTENT%>', $content, $layout);
			$layout = str_replace('<%MENU%>', $menu, $layout);
			$layout = str_replace('<%FOOTER%>', $footer, $layout);
			$layout = str_replace('<%SITE_TAG%>', Request::$_url['site'], $layout);
			$layout = str_replace('<%APP_NAME_TAG%>', Conf::$_conf['preset']['app_name'], $layout);
			$layout = str_replace('<%COPYRIGHT_TAG%>', Conf::$_conf['preset']['copyright'], $layout);
			$layout = str_replace('<%TITLE_TAG%>', $title, $layout);
			$layout = str_replace('<%AUTHOR_TAG%>', Conf::$_conf['preset']['author'], $layout);
			$layout = str_replace('<%DESCRIPTION_TAG%>', $description, $layout);
			$layout = str_replace('<%KEY_WORD_TAG%>', Conf::$_conf['preset']['key_word'], $layout);
			$layout = str_replace('<%LANGUAGE_TAG%>', Language::getLanguage(), $layout);
			
			$layout = str_replace('<%COMMON_CSS_PATH%>', $commonCss, $layout);
			$layout = str_replace('<%ADDITIONAL_CSS_PATH%>', $additionalCss, $layout);
			
			$layout = str_replace('<%COMMON_JS_PATH%>', $commonJs, $layout);
			$layout = str_replace('<%ADDITIONAL_JS_PATH%>', $additionalJs, $layout);
			
			// Replace Tags
			if (!empty(Link::$_link) && is_array(Link::$_link)) {
				foreach (Link::$_link as $key => $value) {
					$layout = str_replace('<%'.strtoupper($key).'_PATH%>', $value, $layout);
				}
			}

			self::$_layout = $layout;
		}

		public static function translation() {
			self::$_layout = Language::translation(self::$_layout);

		}

		/**********************************************************
		* Process layar methods used by user to help render layout.
		*
		**********************************************************/

		public static function getLayout() {
			return self::$_layout;
		}

		public static function getSegment($segment) {
			if (file_exists(Path::$_path['segment'].$segment.'.html') && ($segment = file_get_contents(Path::$_path['segment'].$segment.'.html'))) {
				return $segment; 
			} else {
				throw new \Exception('I can\'t get segment with: "Layout::getSegment()" File: "'.Path::$_path['segment'].$segment.'.html" do not exists');
			}
		}

		public static function replaceSegment($string,$replace,$segment) {
			return str_replace('<%'.strtoupper($string).'%>', $replace, $segment);
		}

		public static function replaceLayout($string,$replace) {
			self::$_layout = str_replace('<%'.strtoupper($string).'%>', $replace, self::$_layout);
		}

		public static function replaceMenu($string,$replace) {
			if (empty(self::$_menu)) {	
				if (file_exists(Path::$_path['segment'].'system/menu.html')) {
					self::$_menu = file_get_contents(Path::$_path['segment'].'system/menu.html');
				} else {
					throw new \Exception('I can\'t autoload menu.html with: "Layout::layoutAutoload()" File: "'.Path::$_path['segment'].'system/menu.html" do not exists');
				}
			}

			self::$_menu = str_replace('<%'.strtoupper($string).'%>', $replace, self::$_menu);
		}

		public static function replaceContent($string,$replace) {
			self::$_content = str_replace('<%'.strtoupper($string).'%>', $replace, self::$_content);
		}

		public static function replaceFooter($string,$replace) {
			if (empty(self::$_footer)) {
				if (file_exists(Path::$_path['segment'].'system/footer.html')) {
					self::$_footer = file_get_contents(Path::$_path['segment'].'system/footer.html');
				} else {
					throw new \Exception('I can\'t autoload footer.html with: "Layout::layoutAutoload()" File: "'.Path::$_path['segment'].'system/footer.html" do not exists');
				}
			}

			self::$_footer = str_replace('<%'.strtoupper($string).'%>', $replace, self::$_footer);
		}

		public static function putContent($html) {
			self::$_content = $html;
		}
		
		public static function putLayout($html) {
			self::$_layout = $html;
		}

		public static function haveContent() {
			if (!empty(self::$_content)) {
				return true;
			} else {
				return false;
			}
		}

		public static function renderMenu($render) {
			if ($render) {
				self::$_renderMenu = true;
			} else {
				self::$_renderMenu = false;
			}
		}

		public static function renderFooter($render) {
			if ($render) {
				self::$_renderFooter = true;
			} else {
				self::$_renderFooter = false;
			}
		}

		public static function addCss($file) {
			if (!empty(Conf::$_conf['path']['additional_css']) && is_array(Conf::$_conf['path']['additional_css'])) {
				array_push(Conf::$_conf['path']['additional_css'], $file);
			} else {
				Conf::$_conf['path']['additional_css'] = array();
				array_push(Conf::$_conf['path']['additional_css'], $file);
			}
		}

		public static function addJs($file) {
			if (!empty(Conf::$_conf['path']['additional_js']) && is_array(Conf::$_conf['path']['additional_js'])) {
				array_push(Conf::$_conf['path']['additional_js'], $file);
			} else {
				Conf::$_conf['path']['additional_js'] = array();
				array_push(Conf::$_conf['path']['additional_js'], $file);
			}
		}

		public static function removeCss($file) {
			if (is_array(Conf::$_conf['path']['common_css']) && count(Conf::$_conf['path']['common_css']) >= 1) {
				$key = array_search($file, Conf::$_conf['path']['common_css']);
				if ($key !== false) {
					unset(Conf::$_conf['path']['common_css'][$key]);
				} else {
					throw new \Exception('I can\'t remove css, file "'.$file.'", do not exists in define_css_js.php.');
				}
			} else {
				throw new \Exception('I can\'t remove css, file "'.$file.'", common js is empty in define_css_js.php.');
			}
		}
		public static function removeJs($file) {
			if (is_array(Conf::$_conf['path']['common_js']) && count(Conf::$_conf['path']['common_js']) >= 1) {
				$key = array_search($file, Conf::$_conf['path']['common_js']);
				if ($key !== false) {
					unset(Conf::$_conf['path']['common_js'][$key]);
				} else {
					throw new \Exception('I can\'t remove js, file "'.$file.'", do not exists in define_css_js.php.');
				}
			} else {
				throw new \Exception('I can\'t remove js, file "'.$file.'", common js is empty in define_css_js.php.');
			}
		}
	}