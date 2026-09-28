<?php 
session_start();
if (isset($_SESSION['role']) && isset($_SESSION['id']) && $_SESSION['role'] == "employee") {
    include "DB_connection.php";
    include "app/Model/Task.php";
    include "app/Model/User.php";
    include "app/Model/Submission.php";
    
    if (!isset($_GET['id'])) {
    	 header("Location: tasks.php");
    	 exit();
    }
    $id = $_GET['id'];
    $task = get_task_by_id($conn, $id);

    if ($task == 0) {
    	 header("Location: tasks.php");
    	 exit();
    }
   $users = get_all_users($conn);
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Edit Task</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">

</head>
<body>
	<input type="checkbox" id="checkbox">
	<?php include "inc/header.php" ?>
	<div class="body">
		<?php include "inc/nav.php" ?>
		<section class="section-1">
			<h4 class="title">Edit Task <a href="my_task.php">Tasks</a></h4>
			<form class="form-1"
			      method="POST"
			      action="app/update-task-employee.php">
			      <?php if (isset($_GET['error'])) {?>
      	  	<div class="danger" role="alert">
			  <?php echo stripcslashes($_GET['error']); ?>
			</div>
      	  <?php } ?>

      	  <?php if (isset($_GET['success'])) {?>
      	  	<div class="success" role="alert">
			  <?php echo stripcslashes($_GET['success']); ?>
			</div>
      	  <?php } ?>
				<div class="input-holder">
					<lable></lable>
					<p><b>Title: </b><?=$task['title']?></p>
				</div>
				<div class="input-holder">
					<lable></lable>
					<p><b>Description: </b><?=$task['description']?></p>
				</div><br>
            <div class="input-holder">
					<lable>Status</lable>
					<select name="status" class="input-1">
						<option 
						      <?php if( $task['status'] == "pending") echo"selected"; ?> >pending</option>
						<option <?php if( $task['status'] == "in_progress") echo"selected"; ?>>in_progress</option>
						<option <?php if( $task['status'] == "completed") echo"selected"; ?>>completed</option>
					</select><br>
				</div>
				<input type="text" name="id" value="<?=$task['id']?>" hidden>

				<button class="edit-btn">Update</button>
			</form>

			<?php if ($task['assigned_to'] == $_SESSION['id']) {
				$my_files = get_submissions_by_task($conn, $task['id']); ?>
			<!-- Upload completed work -->
			<div class="upload-box">
				<h5><i class="fa fa-paperclip" aria-hidden="true"></i> Submit your work</h5>
				<?php if ($task['status'] == "completed") { ?>
				<form method="POST"
				      action="app/upload-task-file.php?id=<?=(int)$task['id']?>"
				      enctype="multipart/form-data">
					<input type="file" name="task_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required>
					<button class="edit-btn">Upload File</button>
				</form>
				<p class="hint">Allowed: PDF, DOC, DOCX, JPG, JPEG, PNG &mdash; max <?=format_file_size(SUBMISSION_MAX_SIZE)?> per file.</p>
				<?php }else { ?>
				<p class="locked">You can upload a file once this task is marked as <b>completed</b>. Set the status to "completed" above and click Update.</p>
				<?php } ?>

				<?php if (!empty($my_files)) { ?>
				<h5 style="margin-top:15px;">Uploaded files</h5>
				<?php $tf_files = $my_files; include "inc/task-files.php"; ?>
				<?php } ?>
			</div>
			<?php } ?>
			
		</section>
	</div>

<script type="text/javascript">
	var active = document.querySelector("#navList li:nth-child(2)");
	active.classList.add("active");
</script>
</body>
</html>
<?php }else{ 
   $em = "First login";
   header("Location: login.php?error=$em");
   exit();
}
 ?>