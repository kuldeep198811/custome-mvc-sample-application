<?php
namespace library\date_picker;
/*
@dev_name : Virendra Kumar
@description: This class is use to create datepicker filed
*/
class dateField{

    public $_id;
    public $_name;
    public $_dateFormat;
    public $_class;
    public $_dateValue;
    public $_placeHolder;
    public $_dateText;
    public $_readOnly;
    public $_startDate;
    public $_endDate;
    public $_site;



    /*
    @dev_name : Virendra Kumar
    @description: Inilize datepicker library
    */
    function __initDateLibs(){
        $_url	=	new \config\config();
        $this->_dateText .= '<script type="text/javascript" src="'.$this->_assetRoot.'/js/date_picker/bootstrap-datepicker.min.js"></script>';
        $this->_dateText .= '<link rel="stylesheet" href="'.$this->_assetRoot.'/css/date_picker/bootstrap-datepicker3.css"/>';
    }

    /*
    @dev_name : Virendra Kumar
    @description: Create date field
    */
    public function __createDateField($_id,$_name=null,$_optional)
    {
         if($_id==''){return 'Please enter date field id ';}
         $this->_id=$_id;
         $this->_name=($_name==null)?$_id:$_name;
         $this->_dateValue=isset($_optional['value'])?$_optional['value']:null;
         $this->_class=isset($_optional['class'])?$_optional['class']:null;
         $this->_dateFormat=(!isset($_optional['format']) || $_optional['format']==null)?'yyyy-mm-dd':$_optional['format'];
         $this->_placeHolder=isset($_optional['placeholder'])?$_optional['placeholder']:null;
         $this->_readOnly=isset($_optional['readonly'])?$_optional['readonly']:null;
         $this->_startDate=(!isset($_optional['startDate']) || $_optional['startDate']==null)?date('Y-m-d', strtotime('-50 years')):date('Y-m-d',strtotime($_optional['startDate']));
         $this->_endDate=(!isset($_optional['endDate']) || $_optional['endDate']==null)?date('Y-m-d', strtotime('+1 years')):date('Y-m-d',strtotime($_optional['endDate']));

         $this->_dateText="<input class='$this->_class' id='$this->_id' name='$this->_name' placeholder='$this->_placeHolder' type='text' $this->_readOnly value='$this->_dateValue'/>";
         $this->_dateText.='<script type="text/javascript">
                $(document).ready(function(){
                $("input#'.$this->_id.'").datepicker({
                format: "'.$this->_dateFormat.'",
                todayHighlight: true,
                autoclose: true,
                startDate: new Date("'.$this->_startDate.'"),
                endDate: new Date("'.$this->_endDate.'")
                });
                });
                </script>';
    }

}
