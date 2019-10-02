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
	
	Class Redirecting
	{
		public function __construct() {
			Layout::renderMenu(false);
			Layout::renderFooter(false);

			Layout::ContentAutoload(Redirect::$_page);
			if (!Render::getAjax()) {
				if (!Layout::haveContent()) {
					Layout::putContent(Redirect::$_message);
				} else {
					Layout::replaceContent('message', Redirect::$_message);
				}
			} else {
				Render::setReply(['reply' => false, 'message' => Redirect::$_message]);
			}
		}
	}