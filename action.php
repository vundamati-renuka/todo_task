<?php
require("config.php");
$todo = array();
// var_dump("hai");exit;
if($_REQUEST['action'] == 'get_data'){
	$query = "SELECT * FROM todos";
	$res = mysqli_query($connection,$query);
	$data = array();
	while($row = mysqli_fetch_assoc($res)){
		$data[] = $row;
	}
	$error = mysqli_error($connection);
	if($error){
		http_response_code(500);
		echo "Db Error";
	}else{	
	echo json_encode($data);
	}
}
if($_REQUEST['action'] == 'add_data'){
	$title = mysqli_escape_string($connection,htmlspecialchars($_POST['title']));
	$detail = mysqli_escape_string($connection,htmlspecialchars($_POST['detail']));
	$date = mysqli_escape_string($connection,htmlspecialchars($_POST['date']));
	$query = "INSERT INTO todos (title,detail,date) VALUES ('".$title."','".$detail."','".$date."')";
	$res = mysqli_query($connection,$query);
	$error = mysqli_error($connection);
	if($error){
		http_response_code(500);
		echo "Db Error";
	}else{	
	echo "Success";
	}
}
if($_REQUEST['action'] == 'update_data'){
	$title = mysqli_escape_string($connection,htmlspecialchars($_POST['title']));
	$detail = mysqli_escape_string($connection,htmlspecialchars($_POST['detail']));
	$date = mysqli_escape_string($connection,htmlspecialchars($_POST['date']));
	$id = mysqli_escape_string($connection,htmlspecialchars($_POST['id']));
	$query = "UPDATE todos SET title='".$title."',detail='".$detail."',date='".$date."' WHERE id='".$id."'";
	$res = mysqli_query($connection,$query);
	$error = mysqli_error($connection);
	echo $error;exit;
	if($error){
		http_response_code(500);
		echo "Db Error";
	}else{	
	echo "Success";
	}
}
if($_REQUEST['action'] == 'get_todo_data'){
	$query = "SELECT * FROM todos WHERE id=".$_REQUEST['id']."";
	$res = mysqli_query($connection,$query);
	$todo = mysqli_fetch_assoc($res);
	$todo['action']=$_REQUEST['act'];
	$error = mysqli_error($connection);
	if($error){
		http_response_code(500);
		echo "Db Error";
	}else{	
		echo json_encode($todo);
	}
}
if($_REQUEST['action'] == 'delete_data'){
	$query = "DELETE FROM todos WHERE id=".$_REQUEST['id']."";
	$res = mysqli_query($connection,$query);
	$error = mysqli_error($connection);
	if($error){
		http_response_code(500);
		echo "Db Error";
	}else{	
		echo "Success";
	}
}
?>