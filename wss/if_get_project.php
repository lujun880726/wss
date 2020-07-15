<?php require_once('config/tank_config.php'); 

$getjson = file_get_contents('php://input');
$dataarr =json_decode($getjson, true);
$token=$dataarr['token'];

$tab=$dataarr['tab'];

$uid = check_token($token);
if($uid <> 3){
    
    mysqli_select_db($tankdb,$database_tankdb);
$query_Recordset_sumtotal = sprintf("SELECT 
							COUNT(*) as count_prj   
							FROM tk_project 	
							WHERE project_to_user = %s", 
								GetSQLValueString($uid, "int")
								);
$Recordset_sumtotal = mysqli_query($tankdb,$query_Recordset_sumtotal) or die(mysqli_error());
$row_Recordset_sumtotal = mysqli_fetch_assoc($Recordset_sumtotal);
$my_totalprj=$row_Recordset_sumtotal['count_prj'];

    $get_function = project_list( $uid, "project_lastupdate", "DESC", "0", $tab );

    $rearr = array(
'summprj'=>$my_totalprj, 	
'list'=>$get_function 	
);

$redata = json_encode($rearr);
echo $redata;
} else {
echo 3;
}
?>
