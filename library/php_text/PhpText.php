<?php
class PHPText
{
	//constructor function
	public function __construct(){

	}

	/*
		*
		@description:: for reading .txt File
		@devloper :: Mohit Singh Rawat
		@param filepath
		return Array of lines 
	*/
	public static function __readFile($_file){
		
		//get file key
		$_fileData = array_keys($_file);
		//get file extension
		$_arrFile = explode('.', $_file[$_fileData['0']]['name']);
    	$_extension = end($_arrFile);
    	$_fileName = $_file[$_fileData['0']]['tmp_name'];

    	if('txt' == $_extension) {

			//open file in read mode
			$_fp = fopen($_fileName, "r"); 

			if(filesize($_fileName)>0){
				//get contents from file
				$_content = fread($_fp, filesize($_fileName)); 
				
				//break line by line the file data
				$_lines = explode("\n", $_content);
				
				//close file pointer
				fclose($_fp);

				//return array of lines
				return $_lines;
			}
			else{
				return ['_empty_file_'=>'No Contents'];
			}	
		}
		else{
			return ['_file_type_error'=>'Invalid file format'];
		}	
	}

	/*
		*
		@description:: for appending text to existing file
		@devloper :: Mohit Singh Rawat
		@param filepath,$data
		return true  or false
	*/
	public static function __writeFile($_filePath,$_data){
		
	
		//get file extension
		$_arrFile = explode('.',basename($_filePath));
		$_extension = end($_arrFile);
    	
    	if('txt' == $_extension) {
			//open file in append mode
			$_handle = fopen($_filePath, 'a') or die('Cannot open file:  '.$_filePath);
			
			//write and return
			return fwrite($_handle, $_data);
		}
		else{
			return ['_file_type_error'=>'Invalid file format'];
		}	
	}

	/*
		*
		@description::Write .json File
		@devloper :: Mohit Singh Rawat
		@param filepath,$data
		return true  or false
	*/
	public static function __writeJsonData($_filePath,$_data){

		$_arrData = array(); // create empty array

		//get file extension
		$_arrFile = explode('.',basename($_filePath));
		$_extension = end($_arrFile);

		if('json' == $_extension) {
			try
			{
			   //Get data from existing json file
			   $_jsonData = file_get_contents($_filePath);

			   // converts json data into array
			   $_arrData = json_decode($_jsonData, true);

			   // Push user data to array
			   array_push($_arrData,$_data);

			   //Convert updated array to JSON
			   $_jsonData = json_encode($_arrData, JSON_PRETTY_PRINT);
			   
			   //write json data into data.json file
				if(file_put_contents($_filePath, $_jsonData)) {
			    	return true;    
			    }
			   else {
			        return false;
			    }    

			}
			catch (Exception $e) {
			    return $e->getMessage();
			}
		}
		else{
			return ['_file_type_error'=>'Invalid file format'];
		}	
	}

	/*
		*
		@description::Read .json File
		@devloper :: Mohit Singh Rawat
		@param filepath,
		return data in array format
	*/
	public static function __readJsonData($_file){

		$_arrData = array(); // create empty array
		
		//get file key
		$_fileData = array_keys($_file); 
		//get file extension
		$_arrFile = explode('.', $_file[$_fileData['0']]['name']);
    	$_extension = end($_arrFile);
    	$_fileName =  $_file[$_fileData['0']]['tmp_name'];


		if('json' == $_extension) {
			try
			{
			   //Get data from existing json file
			   $_jsondata = file_get_contents($_fileName);

			   // converts json data into array
			   $_arrData = json_decode($_jsonData, true);
				
			   return $_arrData;
			}
			catch (Exception $e) {
			    return $e->getMessage();
			}
		}
		else{
			return ['_file_type_error'=>'Invalid file format'];
		}	
	}	

}


