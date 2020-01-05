<?php
namespace library\wysihtml5_editor;
/*
@dev_name : Virendra Kumar
@description: Create Wysihtml5 editor
*/

class Editor{

    public $_id;
    public $_name;
    public $_optional;
    public $_class;
    public $_placeHolder;
    public $_src;
    public $_value;
    public $_rows;
    public $_site;


    /*
    @dev_name : Virendra Kumar
    @description: Inilize wysihtml5 library
    */
    function __iniEditorLibs(){
        $this->_site ='http://localhost/CID-B2C/assets/';
        $this->_src .= '<script type="text/javascript" src="'.$this->_site.'js/wysihtml5_js/bootstrap.min.js"></script>';
        $this->_src .='<script type="text/javascript" src="'.$this->_site.'js/wysihtml5_js/bootstrap3-wysihtml5.all.min.js"></script>';
        $this->_src .= '<link type="text/css" rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">';
        $this->_src .= '<link rel="stylesheet" href="'.$this->_site.'css/wysihtml5_css/bootstrap3-wysihtml5.min.css"/>';
    }

    /*
    @dev_name : Virendra Kumar
    @description: Inilize image upload pulgin in editor
    */
    function __iniEditorImageUpload(){
        $this->_comma="'";
        $this->_src ='<script type="text/javascript">
    $(document).ready(function(){
    /*wysihtml5 editor image upload code start */
    var i = 0;
    var textArea;
    $(".insertImage").click(function(){
        textArea=$(this).attr("id");//alert(textArea);
        $("#upload_image_form_"+textArea)[0].reset();
        $("#upload_image_form_"+textArea+" #heading").css("color", "#FFF");
        $("#upload_image_form_"+textArea+" #custom_resize").val(-1);
        $("#upload_image_form_"+textArea+" .custom_diamention").val(0);
        $("#upload_image_form_"+textArea+" #custom_image_dimentions_div").addClass("hide");
        i = 0;
    });

    $("#upload_image_form_"+textArea+", #insert_image_button").click(function(){
            $("#upload_image_form_"+textArea+",.wysihtml5-sandbox").contents().find("body").find("img").each(function(){
                                    if($(this).attr("src") == "http://" || $(this).attr("src") == "https://" || $(this).attr("src") == "select_file_error"){
                                        $(this).remove();
                                    }
            });

            if(document.getElementById("wysihtml5-image-upload-"+textArea).files.length > 0){
                if(i == 0){
                    i++;
                    /*Add custom resize dimation*/
                    var radioValue = $("#upload_image_form_"+textArea+" input[name='.$this->_comma.'image_resize'.$this->_comma.']:checked").val();
                    if(radioValue==-1){
                        var custom_resize=$("#upload_image_form_"+textArea+" #custom_width").val()+"X"+$("#upload_image_form_"+textArea+" #custom_hight").val();
                        $("#upload_image_form_"+textArea+" #custom_resize").val(custom_resize);
                        $("#upload_image_form_"+textArea+" #custom_image_dimentions_div").addClass("hide");
                    }

                    /****Ajax for save image******/
                        $.ajax({
                            url: "http://localhost/CID-B2C/ajax/editorFileUpload",
                            type: "POST",
                            data:  new FormData($("#upload_image_form_"+textArea)[0]),
                            contentType: false,
                            dataType: "json",
                            cache: false,
                            processData:false,
                            beforeSend : function()
                            {

                            },
                            success: function(returnVal)
                            {
                                if(returnVal.status==1){
                                    $("#upload_image_form_"+textArea+" #custom_resize").val(0);
                                    $("#upload_image_form_"+textArea+" #wysihtml5-image-file-path").val(returnVal.result);
                                    /*$(".bootstrap-wysihtml5-insert-image-modal").modal("hide");*/
                                    $("#upload_image_form_"+textArea+",#insert_image_button").trigger("click");

                                }else{
                                    //$("a#"+textArea).trigger("click");
                                    alert(returnVal.result);
                                }
                                $("#upload_image_form_"+textArea+", .wysihtml5-sandbox").contents().find("body").find("img").each(function(){
                                        if($(this).attr("src") == "http://" || $(this).attr("src") == "https://" || $(this).attr("src") == "select_file_error"){
                                            $(this).remove();
                                        }
                                    });
                            },
                            error: function(jqXHR, textStatus, errorThrown)
                            {
                                $("#upload_image_form_"+textArea+",#insert_image_button").trigger("click");
                                $("#upload_image_form_"+textArea+",.wysihtml5-sandbox").contents().find("body").find("img").each(function(){
                                    if($(this).attr("src") == "http://" || $(this).attr("src") == "https://" || $(this).attr("src") == "select_file_error"){
                                        $(this).remove();
                                    }
                                });
                                alert(textStatus + ": " + errorThrown);
                            }
                        });
                    /****Ajax end******/

                    return false;
                }
            }else{
                alert("Please select image to upload.");
                return false;
            }
    });

        $("#upload_image_form_"+textArea+", .image_resize").click(function(){

                if($(this).attr("value")==-1 && $(this).attr("value")!="0X0"){
                    $("#upload_image_form_"+textArea+" #custom_image_dimentions_div").removeClass("hide");
                    return;
                }

                $("#upload_image_form_"+textArea+" #custom_resize").val(-1);
                $("#upload_image_form_"+textArea+" .custom_diamention").val(0);
                $("#upload_image_form_"+textArea+" #custom_image_dimentions_div").addClass("hide");
                return;

        });

    /*wysihtml5 editor image upload code end */
});
</script>';
    }


    /*
    @dev_name : Virendra Kumar
    @description: Create dynamic wysihtml5 textarea editor
    */
    public function __editorTextField($_id,$_name,$_optional)
    {
         if($_id==''){return 'Please enter date textarea id ';}
         $this->_id=$_id;
         $this->_name=($_name==null)?$_id:$_name;
         $this->_value=isset($_optional['value'])?$_optional['value']:null;
         $this->_class=isset($_optional['class'])?$_optional['class']:null;
         $this->_placeHolder=isset($_optional['placeholder'])?$_optional['placeholder']:null;
         $this->_rows=(!isset($_optional['rows']) || $_optional['rows']==0)?5:$_optional['rows'];

         $this->_textArea='<textarea class="form-control '.$this->_class.'" id="'.$this->_id.'" name="'.$this->_name.'" rows="'.$this->_rows.'" placeholder="'.$this->_placeHolder.'"/></textarea>';
         $this->_comma="'";
         $this->_textArea.='<script type="text/javascript">
                            $(document).ready(function(){
                            $("#'.$this->_id.'").wysihtml5({
                            "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
                            "emphasis": true, //Italics, bold, etc. Default true
                            "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
                            "html": true, //Button which allows you to edit the generated HTML. Default false
                            "link": true, //Button to insert a link. Default true
                            "image": true, //Button to insert an image. Default true,
                            "color": true, //Button to change color of font
                            "table": true,
                            "textAlign": true,
                            "stylesheets": ["'.$this->_site.'css/wysihtml5_css/wysiwyg-color.css", "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"],
                            parser: function(html) {
                            return html;
                            }});


                            $("#'.$this->_id.'").val("'.$this->_value.'");
                            $("#wysihtml5_image_upload_icon").attr("id", "'.$this->_id.'");
                            $("#wysihtml5-image-upload").attr("id", "wysihtml5-image-upload-'.$this->_id.'");
                            $("#'.$this->_id.'").addClass("insertImage");
                            $("#upload_image_form").attr("id", "upload_image_form_'.$this->_id.'");

                            });


                            </script>';
    }



}
