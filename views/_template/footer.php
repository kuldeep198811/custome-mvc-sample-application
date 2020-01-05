
	<script>
	var _assetRoot  	=  	"<?php echo $this->_assetRoot; ?>";
	var _basePath   	=  	"<?php echo $this->_basePath; ?>";
	var _lastStoredStep	=	"<?php echo isset($this->_data['_tempFormData'][0]['step_number'])? '#step-'.$this->_data['_tempFormData'][0]['step_number']:''; ?>";
	var _currentStep	=	window.location.hash;
	</script>

	<?php 
		
		if(isset($this->_listOfCommonJSFiles) && !empty(array_filter($this->_listOfCommonJSFiles))){
			foreach($this->_listOfCommonJSFiles as $js){
	?>
		<script type="text/javascript" src="<?php echo $js; ?>"></script>
	<?php
			}
		}
	?>		
		
	<?php 
		
		if(isset($this->_singleViewJSFiles) && !empty(array_filter($this->_singleViewJSFiles))){
			foreach($this->_singleViewJSFiles as $js){
	?>
		<script type="text/javascript" src="<?php echo $js; ?>"></script>
	<?php
			}
		}
	?>
	
	</body>
</html>