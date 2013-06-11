<?php
//teste
	### If is a post from ajax...
	if (isset($_POST["ajax"])) {	
		###############################
		##----- Some code php -------##
		###############################
		
		###
		$return = "Ajax";
		echo $return;
		exit;
		###
	}
	###
	
	### Mont layout from content
	$this->_content = $this->mountHtml($this->_page);
	$this->_content = str_replace("<%LAYOUT_TAG%>",$this->_layoutPath,$this->_content);
	$this->_content = str_replace("<%BACKGROUND_TAG%>",$this->_path_background_1,$this->_content);
	###
?>
