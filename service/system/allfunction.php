<?php
    
    include('connect.inc.php');
    //error_reporting(NULL);
    
    function chksession_ananda(){
   
	// Session Config //
	session_start();
	
	if($_SESSION[ananda_username]==''){
		  echo "<script> alert('Please login for access system'); </script>";
		  echo "<meta http-equiv='refresh' content='0;url=index.php'>";
	}
	//echo "test".$_SESSION[admin];

    }
    
    function chksession_ananda_v2(){
       
	    // Session Config //
	    session_start();
	    
	    if($_SESSION[ananda_username]==''){
		      echo "<script> alert('Please login for access system'); </script>";
		      echo "<meta http-equiv='refresh' content='0;url=../index.php'>";
	    }
	    //echo "test".$_SESSION[admin];
    
    }


    function cleanspecailchar($data){
        
        $new_data = str_replace('"',"\\'",$data);
        $new_data = str_replace ('/[^\p{L}\p{N}]/u', "" , $new_data);
        
        return $new_data;
    }
    
    function convertdate($date){
        if($date!='')
		{
			return $date->format('Y-m-d');
		}
		else
		{
			return '';
		}

        
    }
    
    function convertdatetime($date){
        
        return $date->format('Y-m-d H:i');
        
    }
    
    function convertutf8($text){
        
        $text = iconv('tis-620','utf-8',$text);
        return $text;
        
    }
	function percen($A,$B)
	{
		$dev=$A/$B;
		$result=$dev*100;
		return $result;
	}
    function converttis620($text){
        
        $text = iconv('utf-8','tis-620',$text);
        return $text;
        
    }
    
    
    function gotopage($link){
        
        return "<META HTTP-EQUIV='Refresh' CONTENT=\"1;URL='$link'\">";
        
    }
    function getStatus($status,$type){
        
        switch($type){
            
            case "m" :
                
                if($status == 1){
                    return "<img src='./images/icon/b1.png'>";
                } else {
                    return "<img src='./images/icon/b0.png'>";
                }
                
                break;
                
            case "s":
                
                if($status == 1){
                    return "<img src='../images/icon/b1.png'>";
                } else {
                    return "<img src='../images/icon/b0.png'>";
                }
                
                break;
            default:
                echo "fix";
                break;
        }
        
    }

    function fixButton($link,$type){
       // echo $link;
        switch($type){
            
            case "m" :
                return "<img src='./images/icon/fix.png' onclick='window.location.href=\"".$link."\"'>";
                break;
                
            case "s":
                return "<img src='../images/icon/fix.png' onclick='window.location.href=\"".$link."\"'>";
                break;
            default:
                break;
        }
        
    }
    
    function delButton($link,$type){
        
        switch($type){
            
            case "m" :
                return "<a href=\"".$link."\" onclick=\"return confirm('กรุณายืนยันการลบอีกครั้ง !!!')\" ><img src='./images/icon/del.png'></a>";
				break;
                
            case "s":
                return "<a href=\"".$link."\" onclick=\"return confirm('กรุณายืนยันการลบอีกครั้ง !!!')\" ><img src='../images/icon/del.png'></a>";
                break;
            default:
                break;
        }
        
    }
    
    function viewButton($link,$type){

         switch($type){
            
            case "m" :
                return "<img src='./images/icon/view.png' onclick='window.location.href=\"".$link."\"'>";
                break;
                
            case "s":
                return "<img src='../images/icon/view.png' onclick='window.location.href=\"".$link."\"'>";
                break;
            default:
                break;
        }
        
    }
?>