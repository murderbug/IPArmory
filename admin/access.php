<?php
require( "../cms/config.php" );

 ob_start();
 session_start();
 require_once '../cms/company_db.php';
 
 
 // if session is not set this will redirect to login page
 if( !isset($_SESSION['user']) ) {
  header("Location: /index.php");
  exit;
 }
 
 $res=mysql_query("SELECT * FROM users WHERE userId=".$_SESSION['user']);
 $userRow=mysql_fetch_array($res);
 
if( ($userRow['userLevel']) == '0' ) {
	 header("Location: /index.php");
	 die;
}
 
?>


<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="description" content="<?php echo $companyName ?> | Admin Panel">
        <meta name="robots" content="noindex" />
		
        <title><?php echo $companyName ?> | Admin Panel</title>
		
		<!-- Mobile Specific Meta
		================================================== -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
		
		<!-- Favicon -->
		<link rel="apple-touch-icon" sizes="57x57" href="/favicon/apple-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="/favicon/apple-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/favicon/apple-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/favicon/apple-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/favicon/apple-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/favicon/apple-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="/favicon/apple-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/favicon/apple-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-icon-180x180.png">
		<link rel="icon" type="image/png" sizes="192x192" href="/favicon/android-icon-192x192.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
		<link rel="manifest" href="/favicon/manifest.json">
		<meta name="msapplication-TileColor" content="#ffffff">
		<meta name="msapplication-TileImage" content="/favicon/ms-icon-144x144.png">
		<meta name="theme-color" content="#ffffff">
		
		<!-- CSS
		================================================== -->
		<!-- Fontawesome Icon font -->
        <link rel="stylesheet" href="/css/font-awesome.min.css">
		<!-- bootstrap.min css -->
        <link rel="stylesheet" href="/css/bootstrap.min.css">
		<!-- Main Stylesheet -->
        <link rel="stylesheet" href="/css/main.php?v=<?php echo rand() ?>">

		
		
		<!-- Modernizer Script for old Browsers -->		
        <script async src="/js/modernizr-2.6.2.min.js"></script>
        
        <style>
        	        	
        	dt{
        	 padding:0px 0px 10px;
        	 font-weight: 600;
        	 font-size: 1.5em;
        	 }
        	 
        	dd{
        		padding: 0px 0 10px;
        		width: auto;
        	}
        	
        </style>
	
    </head>
	
    <body id="body">
    
    <!-- 
    Fixed Navigation
    ==================================== -->
    <header id="navigation" class="navbar navbar-inverse">
        <div class="container">
            <?php include '../includes/admin-nav.php' ?>    		
        </div>
    </header>
    <!--
    End Fixed Navigation
    ==================================== -->

<!-- Start Blog Banner
    ==================================== -->
    <section id="blog-banner">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                   
                    <div class="blog-icon">
                        <i class="fa fa-users fa-5x"></i>
                    </div>
                    <div class="blog-title">
                        <h1><?php echo $companyName ?> Activity</h1>
                    </div>
                    
				</div>     <!-- End col-lg-12 -->
			</div>	    <!-- End row -->
		</div>       <!-- End container -->
	</section>    <!-- End Section -->
    
    
    
            <!-- Start Blog Post Section
    ==================================== -->
    <section id="blog-page">
        <div class="container">
            <div class="row">
               
                <div id="blog-posts" class="col-md-6 col-md-offset-3">
                    <div class="post-item">
                       
                       <!-- Single Post -->
                        <article class="entry">
							<div class="post-excerpt"><div class="text-center" style="font-size: 1.35em;"><i class="fa fa-user-circle"></i> &nbsp; Visitor Activity</div>
							<div class="clearfix">&nbsp;</div>
								
<?php


// get results from database

// $result = mysql_query("SELECT a.*, d.* FROM activity a,downloads d WHERE a.user_id=d.user_id GROUP BY a.userName ORDER BY a.loginDate DESC")

$result = mysql_query("SELECT userName, COUNT(*) as total, MAX(loginDate), userActive FROM activity GROUP BY userName, userActive ORDER BY MAX(loginDate) DESC") or die(mysql_error());


						echo "<dl>";
							
			while($row = mysql_fetch_array( $result )) {	
				
				
				
				$endDate = strtotime($row['MAX(loginDate)']);
				$dateStr = date('F jS\, Y', $endDate);
				
				$users = $row['userName'];
				$firstName = explode(" ", $users);
				
				if($row['total'] == '1'){
					$visits = $row['total'] . ' time.';
				}else{
					$visits = $row['total'] . ' times.';
				}
				
				$login = $dateStr;
				
				if($row['userActive'] == '1'){
					$status = '<span class="color">Active</span>';
				}else{
					$status = '<span class="text-danger">Expired!</span>';
				}
																			
							echo '<dt><strong>' . $users . '</strong> has visited ' . $visits . '</dt>';
							echo '<dd>' . $firstName[0] . '\'s last visit was on <strong>' . $login . '</strong></dd>';
							echo '<dd>' . $firstName[0] . '\'s account status is <strong>' . $status . '</strong></dd>';
							echo '<dd class="clearfix">&nbsp;</dd>';
			
			}
			// close table>
			
						echo "</dl>";

?>     	
                            </article>
                       
              
              <p class="clearfix">&nbsp;</p>
                            
             <article class="entry">
					<div class="post-excerpt"><div class="text-center" style="font-size: 1.35em;"><i class="fa fa-user-circle"></i> &nbsp; File Downloads</div>
					<div class="clearfix">&nbsp;</div>
<?php


// get results from database

$result = mysql_query("SELECT * FROM downloads ORDER BY time") or die(mysql_error());


						echo "<dl>";
						
			while($row = mysql_fetch_array( $result )) {	
				
				
				
				$endDate = strtotime($row['time']);
				$dateStr = date('g:i A \o\n F jS\, Y', $endDate);
				
				$users = $row['userName'];
				$firstName = explode(" ", $users);
				$file = $row['file_id'];
				
				$date = $dateStr;
		
																
							echo '<dt><strong>' . $users . '</strong></dt>';					
							echo '<dd>' . $firstName[0] . ' downloaded File ' . $file . ' at ' . $date . '</dd>';
							echo '<dd class="clearfix">&nbsp;</dd>';
			
			}
			// close table>
			
						echo "</dl>";

?>     	
                            </article>							
                            <!-- End Single Post -->
                        </div>
                    </div>
                    

				</div>	    <!-- End row -->
			</div>       <!-- End container -->
		</section>    <!-- End Section -->
        
	   	<?php include '../includes/footer.php' ?>
		


    </body>
</html>



<!-- 
Essential Scripts
=====================================-->

<!-- Main jQuery -->
<script src="/js/jquery-1.11.0.min.js"></script>
<!-- Bootstrap 3.1 -->
<script src="/js/bootstrap.min.js"></script>
<!-- wow.min Script -->
<script src="/js/wow.min.js"></script>
<!-- Highlight menu item -->
<script src="/js/jquery.nav.js"></script>
<!-- Sticky Nav -->
<script src="/js/jquery.sticky.js"></script>
<!-- Custom js -->
<script src="/js/custom-sub.js"></script>
<?php ob_end_flush(); ?>