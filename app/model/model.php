<?php
	### Include class Helper that have tools like " E-Mail sender, Crop-Image, Counter and very more" 
	require_once("../helper/helper.php");
	###

	###
	class Model extends Helper
	{
		## Run query
		private function query($query)
		{
			if ($this->_connect)
			{ 
				$result = $this->_connect->query($query);
				if ($result)
				{ 
					return $result;
				}else{
					return false;
				}
			}else{
				$this->support();
			}
		}
		###	

		########################################################################
		# Here you must to criet a protected functon to execute the query and                              #
		# send return to controller.                                                                                                   #
		########################################################################
		
		
		##
	}		
?>