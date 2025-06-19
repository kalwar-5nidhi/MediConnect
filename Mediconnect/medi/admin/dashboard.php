<?php
session_start();
error_reporting(0);
include('include/config.php');
// if(strlen($_SESSION['id']==0)) {
//  header('location:logout.php');
// } else {
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Admin | Dashboard</title>
		<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
		<link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
		<link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
		<link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
		<link href="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" media="screen">
		<link href="vendor/select2/select2.min.css" rel="stylesheet" media="screen">
		<link href="vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" media="screen">
		<link href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
		<link rel="stylesheet" href="assets/css/styles.css">
		<link rel="stylesheet" href="assets/css/plugins.css">
		<link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
	</head>
	<body>
		<div id="app">		
<?php include('include/sidebar.php');?>
			<div class="app-content">
				
						<?php include('include/header.php');?>
						
				<!-- end: TOP NAVBAR -->
				<div class="main-content" >
					<div class="wrap-content container" id="container">
						<!-- start: PAGE TITLE -->
						<section id="page-title">
							<div class="row">
								<!-- <div class="col-sm-8">
									<h1 class="mainTitle">Admin | Dashboard</h1>
								</div> -->
								<ol class="breadcrumb">
									<li>
										<span>Admin</span>
									</li>
									<li class="active">
										<span>Dashboard</span>
									</li>
								</ol>
							</div>
						</section>
						<!-- end: PAGE TITLE -->

						<!-- start: DASHBOARD TILES -->
						<div class="row">
							<div class="col-sm-4">
								<div class="panel panel-white no-radius text-center">
									<div class="panel-body">
										<span class="fa-stack fa-2x"> 
											<i class="fa fa-square fa-stack-2x text-primary"></i> 
											<i class="fa fa-users fa-stack-1x fa-inverse"></i> 
										</span>
										<h2 class="StepTitle">Manage Hospitals/Clinics</h2>
										<p class="links cl-effect-1">
											<a href="manage-healthcare.php">
												<?php 
												$result = mysqli_query($con,"SELECT * FROM healthcare_facilities");
												$num_rows = mysqli_num_rows($result);
												?>
												Total Healthcare : <?php echo htmlentities($num_rows); ?>
											</a>
										</p>
									</div>
								</div>
							</div>

							<div class="col-sm-4">
								<div class="panel panel-white no-radius text-center">
									<div class="panel-body">
										<span class="fa-stack fa-2x"> 
											<i class="fa fa-square fa-stack-2x text-primary"></i> 
											<i class="fa fa-id-badge fa-stack-1x fa-inverse"></i> 
										</span>
										<h2 class="StepTitle">Manage Staffs</h2>
										<p class="cl-effect-1">
											<a href="manage-staffs.php">
												<?php 
												$result1 = mysqli_query($con,"SELECT * FROM staff ");
												$num_rows1 = mysqli_num_rows($result1);
												?>
												Total Staffs : <?php echo htmlentities($num_rows1); ?>
											</a>
										</p>
									</div>
								</div>
							</div>

							<div class="col-sm-4">
								<div class="panel panel-white no-radius text-center">
									<div class="panel-body">
										<span class="fa-stack fa-2x"> 
											<i class="fa fa-square fa-stack-2x text-primary"></i> 
											<i class="fa fa-calendar-check-o fa-stack-1x fa-inverse"></i> 
										</span>
										<h2 class="StepTitle">Manage Medicines</h2>
										<p class="links cl-effect-1">
											<a href="medicine-management.php">
												<?php 
												$sql= mysqli_query($con,"SELECT * FROM medicines");
												$num_rows2 = mysqli_num_rows($sql);
												?>
												Total Medicines : <?php echo htmlentities($num_rows2); ?>	
											</a>
										</p>
									</div>
								</div>
							</div>


							<div class="col-sm-4">
								<div class="panel panel-white no-radius text-center">
									<div class="panel-body">
										<span class="fa-stack fa-2x"> 
											<i class="fa fa-square fa-stack-2x text-primary"></i> 
											<i class="fa fa-users fa-stack-1x fa-inverse"></i> 
										</span>
										<h2 class="StepTitle">Delivery Management</h2>
										<p class="links cl-effect-1">
											<a href="manage-delivery.php">
												<?php 
												$result = mysqli_query($con,"SELECT * FROM order_items ");
												$num_rows = mysqli_num_rows($result);
												?>
												Total Delivery : <?php echo htmlentities($num_rows); ?>
											</a>
										</p>
									</div>
								</div>
							</div>

							
							<div class="col-sm-4">
								<div class="panel panel-white no-radius text-center">
									<div class="panel-body">
										<span class="fa-stack fa-2x"> 
											<i class="fa fa-square fa-stack-2x text-primary"></i> 
											<i class="fa fa-users fa-stack-1x fa-inverse"></i> 
										</span>
										<h2 class="StepTitle">Manage Users</h2>
										<p class="links cl-effect-1">
										    <a href="manage-users.php">
												<?php 
												$result = mysqli_query($con,"SELECT * FROM users ");
												$num_rows = mysqli_num_rows($result);
												?>
												Total Users : <?php echo htmlentities($num_rows); ?>
											</a>
										</p>
									</div>
								</div>
							</div>

							
							
							<div class="col-sm-4">
								<div class="panel panel-white no-radius text-center">
									<div class="panel-body">
										<span class="fa-stack fa-2x"> 
											<i class="fa fa-square fa-stack-2x text-primary"></i> 
											<i class="fa fa-users fa-stack-1x fa-inverse"></i> 
										</span>
										<h2 class="StepTitle">Payment/Transaction</h2>
										<p class="links cl-effect-1">
											<a href="payment-transactions.php">
												<?php 
												$result = mysqli_query($con,"SELECT * FROM payment_transactions ");
												$num_rows = mysqli_num_rows($result);
												?>
												Manage Payments : <?php echo htmlentities($num_rows); ?>
											</a>
										</p>
									</div>
								</div>
							</div>

							<div class="col-sm-4">
								<div class="panel panel-white no-radius text-center">
									<div class="panel-body">
										<span class="fa-stack fa-2x"> 
											<i class="fa fa-square fa-stack-2x text-primary"></i> 
											<i class="fa fa-envelope-o fa-stack-1x fa-inverse"></i> 
										</span>
										<h2 class="StepTitle">Reviews AND Feedback</h2>
										<p class="links cl-effect-1">
											<a href="reviews-feedback.php">
												<?php 
												$sql= mysqli_query($con,"SELECT * FROM reviews ");
												$num_rows22 = mysqli_num_rows($sql);
												?>
												Total Reviews and Feedback : <?php echo htmlentities($num_rows22); ?>	
											</a>
										</p>
									</div>
								</div>
							</div>
						</div>
						<!-- end: DASHBOARD TILES -->
					
					</div>
				</div>
			</div>

			<!-- start: FOOTER -->
	<?php include('include/footer.php');?>
			<!-- end: FOOTER -->
		
			<!-- start: SETTINGS -->
	<?php include('include/setting.php');?>
		
			<!-- end: SETTINGS -->
		</div>

		<script src="vendor/jquery/jquery.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="vendor/modernizr/modernizr.js"></script>
		<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
		<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
		<script src="vendor/switchery/switchery.min.js"></script>
		<script src="assets/js/main.js"></script>
		<script src="assets/js/form-elements.js"></script>
		<script>
			jQuery(document).ready(function() {
				Main.init();
				FormElements.init();
			});
		</script>
	</body>
</html>

