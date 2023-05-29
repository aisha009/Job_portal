<?php
require_once "config.php";

if(!isset($_SESSION['username']))
{
    header("location: login.php");
    exit;
}

$sql = "SELECT * FROM job_announcements where ACTIVE=1";
$result = mysqli_query($conn, $sql);

$ann = array();

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        $ann[] = $row;
    }
} 

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Job Portal</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.html">Welcome!</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
                </div>
            </form>
            <!-- Navbar-->
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!">View Profile</a></li>
                        <li><a class="dropdown-item" href="#!">Change Password</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <a class="nav-link" href="Dashboard.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Dashboard
                            </a>
							
							<a class="nav-link" href="challan_d.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Download Challan
                            </a>
							<a class="nav-link" href="#">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Profile
                            </a>
							<a class="nav-link" href="logout.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Logout
                            </a>
                            
                            
                            
                            
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        <?php
							echo $_SESSION['username'];
						?>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Welcome to Job Portal</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Job Announcements</li>
                        </ol>
                        <div class="row">
                            
                            <?php
								if(count($ann)>0){
									$nic=$_SESSION['username'];
									$sql = "SELECT * FROM user_registration where CNIC_NO=$nic";
									$result = mysqli_query($conn, $sql);
									$row = mysqli_fetch_assoc($result);
									
									$USER_ID=0;
									if(count($row)>0){
										$USER_ID = $row['USER_ID'];
									}
									
									
									foreach($ann as $job){
										$originalDate = $job['END_DATE'];
										$originalDate = date("d-m-Y", strtotime($originalDate));
										$JOB_ID = $job['JOB_ID'];
										
										$sql = "SELECT * FROM applications where USER_ID=$USER_ID AND JOB_ID=$JOB_ID";
										$result = mysqli_query($conn, $sql);
										$row = mysqli_fetch_assoc($result);
										
										//echo "<pre>";
										//print_r($row);
										//exit();

										$currentDate = date('Y-m-d');	
										
										if(!empty($row)>0){
											$url = '#';
											$tag = "Already applied";
										}elseif(strtotime($originalDate)>strtotime($currentDate)){
											$url="challan.php?ID=$JOB_ID";
											$tag = "Apply Now";
										}else{
											$url = "#";
											$tag = "Date Expired";
										}
										
									?>
									
										<div class="col-xl-3 col-md-6">
											<div class="card bg-success text-white mb-4">
												<div class="card-body" align='center'><?=$job['JOB_TITLE']?></div>
												<div class="card-body" align='center'>Valid upto: <?=$originalDate?></div>
												<div class="card-footer d-flex align-items-center justify-content-between">
													<a class="small text-white stretched-link" href="#">View Details</a>
													
														<a class="small text-white stretched-link" href="<?=$url?>"><?=$tag?></a>
													
													<div class="small text-white"><i class="fas fa-angle-right"></i></div>
												</div>
											</div>
										</div>									
									
									<?php
								}}
								
							?>
                            
                        </div>
                        
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                MY APPLICATIONS
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Applied For</th>
                                            <th>Job Description</th>
                                            <th>Application Status</th>
                                            <th>Applied Date</th>
                                            <th>Download Challan</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Applied For</th>
                                            <th>Job Description</th>
                                            <th>Application Status</th>
                                            <th>Applied Date</th>
											<th>Download Challan</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
											$nic=$_SESSION['username'];
											$sql = "SELECT * FROM applications AS app JOIN user_registration AS ur ON app.USER_ID = ur.USER_ID JOIN job_announcements AS ja ON app.JOB_ID = ja.JOB_ID where ur.CNIC_NO=$nic";
											$result = mysqli_query($conn, $sql);

											$res = array();

											if (mysqli_num_rows($result) > 0) {
												while($row = mysqli_fetch_assoc($result)) {
													$res[] = $row;
												}
											}
											
											if(count($res)>0){												
												foreach($res as $row){

                                                    if($row['STATUS']==1){
                                                        $status="proceesing";
                                                    }else{
                                                        $status="interviewed";
                                                    }
																										
												?>
												<tr>
													<td><?=$row['JOB_TITLE']?></td>
													<td><?=$row['JOB_DESCRIPTION']?></td>
													<td><?=$status?></td>
													<td><?=$row['APPLICATION_DATE']?></td>
													<td><a target="_new" href="challan.php?ID=<?=$row['JOB_ID']?>">Download Challan</a></td>
												</tr>																								
												<?php
											}
											}
										?>		
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Your Website 2022</div>
                            <div>
                                <a href="#">Privacy Policy</a>
                                &middot;
                                <a href="#">Terms &amp; Conditions</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>
