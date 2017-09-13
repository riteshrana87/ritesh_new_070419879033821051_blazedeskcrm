<?php
// Connection
/*print_r($_POST);
die('here');*/
//$conn = mysqli_connect("localhost","root","","mychat");
/*error_reporting(E_ALL ^ E_DEPRECATED);
$conn = mysql_connect("localhost", 'root', '');
$db = mysql_select_db("mychat", $conn);
$json = '';

if(isset($_GET['rq'])):
	switch($_GET['rq']):
		case 'new':
			/*print_r($_POST);
			die('here');*/
			/*$msg = $_POST['msg'];
			$myid = $_POST['mid'];
			$fid = $_POST['fid'];
			if(empty($msg)){
				//$json = array('status' => 0, 'msg'=> 'Enter your message!.');
			}else{
				
				$qur = mysql_query('insert into msg set `to`="'.$fid.'", `from`="'.$myid.'", `msg`="'.$msg.'", `status`="1"');
				/*print_r($qur);
				die();*/
				/*if($qur){
					//echo mysql_insert_id();
					//die();
					$qurGet = mysql_query("select * from msg where id='".mysql_insert_id()."'");
					while($row = mysql_fetch_array($qurGet)){
						$json = array('status' => 1, 'msg' => $row['msg'], 'lid' => mysql_insert_id(), 'time' => $row['time'],'to'=>$row['to'],'from'=>$row['from']);
						/*print_r($json);
						die();*/
					/*}
				}else{
					$json = array('status' => 0, 'msg'=> 'Unable to process request.');
				}
			}
		break;
		case 'msg':
		
			$myid = $_POST['mid'];
			$fid = $_POST['fid'];
			$lid = $_POST['lid'];
			if(empty($myid)){
				
			}else{
				
				//print_r($_POST);
				$qur = mysql_query("select * from msg where `to`='$myid' && `from`='$fid' && `status`=1");
				/*echo $qur;
				die();*/
				/*if(mysql_num_rows($qur) > 0){
					$json = array('status' => 1);
					/*print_r($json);
					die('here');*/
				/*}else{
					$json = array('status' => 0);
				}
			}
		break;
		case 'NewMsg':
			$myid = $_POST['mid'];
			$fid = $_POST['fid'];

			$qur = mysql_query("select * from msg where `to`='$myid' && `from`='$fid' && `status`=1 order by id desc limit 1");
			while($rw = mysql_fetch_array($qur)){
				$json = array('status' => 1, 'msg' => '<div>'.$rw['msg'].'</div>', 'lid' => $rw['id'], 'time'=> $rw['time'],'to'=>$rw['to'],'from'=>$rw['from']);
			}
			// update status
			$up = mysql_query("UPDATE `msg` SET  `status` = '0' WHERE `to`='$myid' && `from`='$fid'");
		break;
	endswitch;
endif;

@mysql_close($conn);*/
header('Content-type: application/json');
echo json_encode($json);
?>