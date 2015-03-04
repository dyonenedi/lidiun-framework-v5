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

	use Lidiun\Autoload;
	use Lidiun\System;

	try {
		#######################################################################################
		################################# Include Configuration ###############################
		#######################################################################################

		$conf['database'] = include_once('../config/database_config.php');
		if (!$conf['database']) {
			throw new \Exception('I can\'t find "database_config.php" file in: '.__FILE__.' on line: '.__LINE__);
		}

		$conf['path'] = include_once('../config/path_config.php');
		if (!$conf['path']) {
			throw new \Exception('I can\'t find "path_config.php" file in: '.__FILE__.' on line: '.__LINE__);
		}

		$conf['preset'] = include_once('../config/preset_config.php');
		if (!$conf['preset']) {
			throw new \Exception('I can\'t find "preset_config.php" file in: '.__FILE__.' on line: '.__LINE__);
		}
		#######################################################################################
		############################## Include Autoload and System ############################
		#######################################################################################

		if (!include_once('../../lidiun/autoload.php')) {
			throw new \Exception('I can\'t find "autoload.php" file in: '.__FILE__.' on line: '.__LINE__);
		}

		if (!include_once('../../lidiun/system.php')) {
			throw new \Exception('I can\'t find "system.php" file in: '.__FILE__.' on line: '.__LINE__);
		}

		Autoload::init();
		
		$System = new System($conf);
		$System = null;
	} catch (Exception $e) {
		$System = null;
		echo '<body style="background:#000; font-size: 16px; color: #FFF;"><pre style="color: white;">'.$e->getMessage().'</pre><pre style="color: green;">'.$e->getTraceAsString().'</pre></body>';
	}