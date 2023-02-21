<?php
//require("config.php");
require("action.php");
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<!-- CSS only -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
	<!-- JavaScript Bundle with Popper -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
	<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
	<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

	<title>Todos Application</title>
	<style>
		.bg{
			display: flex;
			flex-direction: row;
			justify-content: center;
		}
		.todos{
			margin:20px;
		}
		.todo{
			width:500px;
			background-color: lightpink;
			padding:5px 15px;
			padding-bottom:15px;
			border-radius:10px;
		}
		.editBtn{
			border:1px solid #000000;
			background-color: transparent;
			font-size: 16px	;
			width:80px;
			padding:10px;
			margin-right: 10px;
		}
		.deleteBtn{
			border:none;
			background-color: royalblue;
			font-size: 16px	;
			width:120px;
			padding:10px;
			color:#ffffff;
		}
		.para{
			padding:5px;
		}
		.edit{
			margin: 20px;
		}
		.date{
			position: relative;
		}
		.header{
			text-align:center;
			padding: 10px;
		}
		.form-input{
			width:100%;
			height: 32px;
		}
		.form-txt{
			width:100%;
			height: 200px;
		}
	</style>
</head>
<body>
	<div class="bg">
		<div class="todos" id="todos"></div>
		<div class="edit">
			<form id="frm">
				<div class='todo'>
					<div class="header form-group">
						<label class="form-label" for="title">*Title</label>
					</div>
					<div>
						<input class="form-control" type="text" name="title" id="title">
					</div>
					<div class="header">
						<label for="detail">*Details</label>
					</div>
					<div>
						<textarea class="form-control form-txt" type="text" name="detail" id="detail"></textarea>
					</div>
					<div class="header">
						<label for="date">*Date</label>
					</div>
					<div class="date">
						<input class="form-control" type="text" name="date" id="date">
					</div>
					<div class="header">
						<input type="hidden" name="action" id="action" value="add_data">
						<input type="hidden" name="id" id="id">
						<input class="btn btn-light" type="button" onclick="addTodo()" name="submit" value="submit">
					</div>				
				</div>
			</form>
		</div>
	</div>
	<script type="text/javascript">
		function getTodos(){
			$.ajax({
				type: 'POST',
				url: "action.php?action=get_data",
				success: function(resultData) {
					var data = JSON.parse(resultData)
					var html = "";
					for (var i in data ){
						html+=`
						<div class='card todo shadow mb-4'>
						<p class='text-center'>${data[i].title}</p>
						<hr>
						<p class='para'>${data[i].detail}</p>
						<hr>
						<p class='para'>${data[i].date}</p>
						<div style='text-align:right'>
						<button class='btn btn-light' onclick='editTodo(${data[i].id})'>Edit</button>
						<button class='btn btn-primary' onclick='deleteTodo(${data[i].id})'><i class="fa-solid fa-trash-can"></i> Remove</button>
						</div>
						</div>`
					}
					document.getElementById('todos').innerHTML=html;
				},
				error:function(e){
					alert("error occured");
				}
			});
		}
		function addTodo(){
			var err = 0;
			if($('#title').val()=="" && $('#detail').val()=="" && $('#date').val()==""){
				alert("Please Enter Todo Data");
				err+=1
			}
			else if($('#title').val()==""){
				err+=1
				alert('Please Enter Title')
			}
			else if($('#detail').val()==""){
				err+=1
				alert('Please Enter Detail')
			}
			else if($('#date').val()==""){
				err+=1
				alert('Please Enter Date')
			}
			else if(err==0){
				var formData = $('#frm').serialize();
				console.log(formData);
				$.ajax({
					type: 'POST',
					url: "action.php",
					data: formData,
					success: function(resultData) {
						alert("Todo Added") ;
						getTodos();
						document.getElementById('frm').reset();
					},
					error:function(e){
						alert("error occured"+e);
						console.log(e);
					}
				});
			}
		}
		function editTodo(id){
			var url_data = "action.php?action=get_todo_data&act=update_data&id="+id;
			$.ajax({
				type: 'POST',
				url: url_data,
				success: function(resultData) {
					var data = JSON.parse(resultData);
					$('#title').val(data.title);
					$('#detail').val(data.detail);
					$('#date').val(data.date);
					$('#action').val(data.action);
					$('#id').val(data.id);
				},
				error:function(e){
					alert("error occured");
				}
			});
		}
		function deleteTodo(id){
			var url_data = "action.php?action=delete_data&id="+id;
			if(confirm("Are you really want to delete Todo?")==true){
				$.ajax({
					type: 'POST',
					url: url_data,
					success: function(resultData) {
						getTodos();
						alert("Todo Deleted SuccessFully!")
						document.getElementById('frm').reset();
					},
					error:function(e){
						alert("error occured");
					}
				});
			}
		}
		$("#date").flatpickr({
			enableTime: true,
			dateFormat: "Y-m-d H:i",
		});
		getTodos();
	</script>
</body>
</html>