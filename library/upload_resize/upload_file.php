<form id="uploadForm" action="test.php" method="post" enctype="multipart/form-data">
<label>Upload Image File:</label><br/>
<input name="fileToUpload" type="file" />
<input type="submit" value="Submit" class="btnSubmit" />
</form>
<div id="res"></div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

<script type="text/javascript">
$(document).ready(function (e){
	$('#res').html('');
$("#uploadForm").on('submit',(function(e){
e.preventDefault();
$.ajax({
url: "test.php",
type: "POST",
data:  new FormData(this),
contentType: false,
cache: false,
processData:false,
success: function(data){
 $('#res').html(data);
 $('#uploadForm').trigger("reset");
},
error: function(){} 	        
});
}));
});
</script>