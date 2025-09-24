<?php
include "../includes/common.php";

$PAGE_TITLE .= 'Dashboard';
$total_sp = GetXFromYID("select count(*) from service_providers where cStatus!='X' ");
$total_platinum = GetXFromYID("select count(*) from service_providers where cStatus!='X' and cUsertype='P' ");
$total_customers = GetXFromYID("select count(*) from customers where cStatus!='X' ");
$total_earnings = GetXFromYID("SELECT SUM(amount) from transaction WHERE payment_status='S' and date_format(pdate,'%Y')='" . THIS_YEAR . "' ");
$MONTHWISE_TOTAL_THIS_YEAR = GetXArrFromYID("SELECT DATE_FORMAT(pdate, '%m') AS month,SUM(amount) AS total_amount FROM transaction where payment_status='S' and date_format(pdate,'%Y')='" . THIS_YEAR . "' GROUP BY DATE_FORMAT(pdate, '%m') ORDER BY month ", '3');
$MONTHWISE_TOTAL_LAST_YEAR = GetXArrFromYID("SELECT DATE_FORMAT(pdate, '%m') AS month,SUM(amount) AS total_amount FROM transaction where payment_status='S' and date_format(pdate,'%Y')='" . LAST_YEAR . "' GROUP BY DATE_FORMAT(pdate, '%m') ORDER BY month ", '3');
//DFA($MONTHWISE_TOTAL);
$MONTH_ARR = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
$MONTH_ARR_DATA_THIS_YEAR = $MONTH_ARR_DATA_LAST_YEAR = array();
foreach ($MONTH_ARR as $value) {
	$AMT = (isset($MONTHWISE_TOTAL_THIS_YEAR[$value])) ? $MONTHWISE_TOTAL_THIS_YEAR[$value] : 0;
	array_push($MONTH_ARR_DATA_THIS_YEAR, $AMT);
}

foreach ($MONTH_ARR as $value) {
	$AMT = (isset($MONTHWISE_TOTAL_LAST_YEAR[$value])) ? $MONTHWISE_TOTAL_LAST_YEAR[$value] : 0;
	array_push($MONTH_ARR_DATA_LAST_YEAR, $AMT);
}
// DFA($MONTH_ARR_DATA_THIS_YEAR);
// DFA($MONTH_ARR_DATA_LAST_YEAR);
if ($sess_user_level == '0' || $sess_user_level == '1') {
} else {
	header('location:home2.php');
	exit;
}


//DFA(GET_EMAILCOUNT());



//echo $EMAIL_LEFT;
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo $PAGE_TITLE ?></title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Tempusdominus Bbootstrap 4 -->
	<link rel="stylesheet" href="../plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
	<!-- iCheck -->
	<link rel="stylesheet" href="../plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<!-- JQVMap -->
	<link rel="stylesheet" href="../plugins/jqvmap/jqvmap.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="../dist/css/adminlte.min.css">
	<!-- overlayScrollbars -->
	<link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<!-- Daterange picker -->
	<link rel="stylesheet" href="../plugins/daterangepicker/daterangepicker.css">
	<!-- summernote -->
	<link rel="stylesheet" href="../plugins/summernote/summernote-bs4.css">
	<!-- Google Font: Source Sans Pro -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
	<div class="wrapper">

		<?php include 'load.header.php' ?>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<div class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 text-dark">Dashboard</h1>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="../index.php">Home</a></li>
								<li class="breadcrumb-item active">Dashboard v1</li>
							</ol>
						</div><!-- /.col -->
					</div><!-- /.row -->
				</div><!-- /.container-fluid -->
			</div>
			<!-- /.content-header -->

			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">
					<!-- Small boxes (Stat box) -->
					<div class="row">
						<div class="col-lg-3 col-6">
							<!-- small box -->
							<div class="small-box bg-info">
								<div class="inner">
									<h3><?php echo $total_sp; ?></h3>

									<p>Service providers</p>
								</div>
								<div class="icon">
									<i class="ion ion-bag"></i>
								</div>
								<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
							</div>
						</div>
						<!-- ./col -->
						<div class="col-lg-3 col-6">
							<!-- small box -->
							<div class="small-box bg-success">
								<div class="inner">
									<h3><?php echo $total_customers; ?><sup style="font-size: 20px"></sup></h3>

									<p>Customers </p>
								</div>
								<div class="icon">
									<i class="ion ion-stats-bars"></i>
								</div>
								<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
							</div>
						</div>
						<!-- ./col -->
						<div class="col-lg-3 col-6">
							<!-- small box -->
							<div class="small-box bg-warning">
								<div class="inner">
									<h3><?php echo GetXFromYID("select count(*) from credit_request where cApprovalStatus='P'"); ?></h3>

									<p>Pending Credit Requests</p>
								</div>
								<div class="icon">
									<i class="ion ion-person-add"></i>
								</div>
								<a href="credit_requests.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
							</div>
						</div>

						<div class="col-lg-3 col-6">
							<!-- small box -->
							<div class="small-box bg-danger">
								<div class="inner">
									<h3><?php echo $total_platinum; ?></h3>

									<p>Platinum SPs</p>
								</div>
								<div class="icon">
									<i class="ion ion-person-add"></i>
								</div>
								<a href="platinum_service_providers.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
							</div>
						</div>
						<!-- ./col -->
						<!-- <div class="col-lg-3 col-6">
							
							<div class="small-box bg-danger">
								<div class="inner">
									<h3>65</h3>

									<p>Unique Visitors</p>
								</div>
								<div class="icon">
									<i class="ion ion-pie-graph"></i>
								</div>
								<a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
							</div>
						</div> -->
						<!-- ./col -->
					</div>

					<div class="row">
						<div class="col-lg-3 col-6 p-3">
							<button class="btn btn-primary" onclick="GoToPage('admin_calendar_setup.php');" type="button">Click here to Set Up calendar Access</button>
						</div>
					</div>
					<!-- /.row -->
					<!-- Main row -->
					<div class="row">
						<!-- Left col -->
						<section class="col-lg-12 connectedSortable">
							<!-- Custom tabs (Charts with tabs)-->
							<div class="card">
								<div class="card-header border-0">
									<div class="d-flex justify-content-between">
										<h3 class="card-title">Earnings</h3>
										<a href="javascript:void(0);">View Report</a>
									</div>
								</div>
								<div class="card-body">
									<div class="d-flex">
										<p class="d-flex flex-column">
											<span class="text-bold text-lg">$<?php echo $total_earnings; ?></span>
											<span>Earnings Over Time</span>
										</p>
										<p class="ml-auto d-flex flex-column text-right">
											<span class="text-success">
												<i class="fas fa-arrow-up"></i> 33.1%
											</span>
											<span class="text-muted">Since last month</span>
										</p>
									</div>
									<!-- /.d-flex -->

									<div class="position-relative mb-4">
										<canvas id="sales-chart" height="200"></canvas>
									</div>

									<div class="d-flex flex-row justify-content-end">
										<span class="mr-2">
											<i class="fas fa-square text-primary"></i> <?php echo THIS_YEAR; ?>
										</span>

										<span>
											<i class="fas fa-square text-gray"></i> <?php echo LAST_YEAR; ?>
										</span>
									</div>
								</div>
							</div>
							<!-- /.card -->

							<!-- DIRECT CHAT -->

							<!--/.direct-chat -->

							<!-- TO DO List -->

							<!-- /.card -->
						</section>
						<!-- /.Left col -->
						<!-- right col (We are only adding the ID to make the widgets sortable)-->

						<!-- right col -->
					</div>
					<!-- /.row (main row) -->
				</div><!-- /.container-fluid -->
			</section>
			<!-- /.content -->
		</div>
		<!-- /.content-wrapper -->
		<?php include 'load.footer.php' ?>
		<!-- /.control-sidebar -->
	</div>
	<!-- ./wrapper -->

	<!-- jQuery -->
	<script src="../plugins/jquery/jquery.min.js"></script>
	<!-- jQuery UI 1.11.4 -->
	<script src="../plugins/jquery-ui/jquery-ui.min.js"></script>
	<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	<script>
		$.widget.bridge('uibutton', $.ui.button)
	</script>
	<!-- Bootstrap 4 -->
	<script src="../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- ChartJS -->
	<script src="../plugins/chart.js/Chart.min.js"></script>
	<!-- Sparkline -->
	<!-- <script src="../plugins/sparklines/sparkline.js"></script> -->
	<!-- JQVMap -->
	<script src="../plugins/jqvmap/jquery.vmap.min.js"></script>
	<script src="../plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
	<!-- jQuery Knob Chart -->
	<script src="../plugins/jquery-knob/jquery.knob.min.js"></script>
	<!-- daterangepicker -->
	<script src="../plugins/moment/moment.min.js"></script>
	<script src="../plugins/daterangepicker/daterangepicker.js"></script>
	<!-- Tempusdominus Bootstrap 4 -->
	<script src="../plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
	<!-- Summernote -->
	<script src="../plugins/summernote/summernote-bs4.min.js"></script>
	<!-- overlayScrollbars -->
	<script src="../plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
	<!-- AdminLTE App -->
	<script src="../dist/js/adminlte.js"></script>
	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	<!-- <script src="../dist/js/pages/dashboard3.js"></script> -->
	<!-- AdminLTE for demo purposes -->
	<script src="../dist/js/demo.js"></script>
	<script src="../scripts/common.js"></script>
	<script>
		$(function() {
			'use strict'

			var ticksStyle = {
				fontColor: '#495057',
				fontStyle: 'bold'
			}

			var mode = 'index'
			var intersect = true
			var THIS_Y = <?php echo json_encode($MONTH_ARR_DATA_THIS_YEAR); ?>;
			var LAST_Y = <?php echo json_encode($MONTH_ARR_DATA_LAST_YEAR); ?>;
			//$.widget.bridge('uibutton', $.ui.button)
			/** add active class and stay opened when selected */
			var url = window.location;
			var $salesChart = $('#sales-chart')
			var salesChart = new Chart($salesChart, {
				type: 'bar',
				data: {
					labels: ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'],
					datasets: [{
							backgroundColor: '#007bff',
							borderColor: '#007bff',
							data: THIS_Y
						},
						{
							backgroundColor: '#ced4da',
							borderColor: '#ced4da',
							data: LAST_Y
						}

					]
				},
				options: {
					maintainAspectRatio: false,
					tooltips: {
						mode: mode,
						intersect: intersect
					},
					hover: {
						mode: mode,
						intersect: intersect
					},
					legend: {
						display: false
					},
					scales: {
						yAxes: [{
							// display: false,
							gridLines: {
								display: true,
								lineWidth: '4px',
								color: 'rgba(0, 0, 0, .2)',
								zeroLineColor: 'transparent'
							},
							ticks: $.extend({
								beginAtZero: true,

								// Include a dollar sign in the ticks
								callback: function(value, index, values) {
									if (value >= 1000) {
										value /= 1000
										value += 'k'
									}
									return '$' + value
								}
							}, ticksStyle)
						}],
						xAxes: [{
							display: true,
							gridLines: {
								display: false
							},
							ticks: ticksStyle
						}]
					}
				}
			})


		})
	</script>
</body>

</html>