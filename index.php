<?php
	include("magickeditor/php/magickeditor.php");
	define('MAGICKBASE', 'http://localhost/magickeditor/index.php');
	define('MAGICKDIRECTORY', 'files/uploads');
?>
<!DOCTYPE html>
<html lang="en-us">
	<head>
		<meta charset="utf-8">
		<title>MagickEditor</title>
		<link rel="icon" href="favicon.ico" type="image/x-icon">
		<meta name="Keywords" content="HTML,CSS,DOM,JavaScript,jQuery,XML,AJAX,ASP.NET,W3C,tutorials,programming,learning,guide,primer,lessons,school,howto,reference,examples,source code,demos,color tables,Cascading Style Sheets,Active Server Pages,Programming,Development.Webbuilder,Sitebuilder,Webmaster">
		<meta name="Description" content="HTML CSS JavaScript jQuery AJAX XML ASP.NET SQL Tutorials References Examples">
		<meta name="viewport" content="width=device-width">
		
		<!-- JQueryUI -->
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
		<link rel="stylesheet" href="/resources/demos/style.css">		
		<!-- JQueryUI ends -->
		
		<script src="magickeditor/js/jcrop/jquery.Jcrop.min.js"></script>
		<script> var magickBase = '<?php echo MAGICKBASE; ?>'; </script>
		<script src="magickeditor/js/magickeditor.js"></script>		
		
		<link rel="stylesheet" type="text/css" href="magickeditor/css/magickeditor.css">
		<link rel="stylesheet" type="text/css" href="magickeditor/js/jcrop/jquery.Jcrop.min.css">
		
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
		<!-- Latest compiled and minified JavaScript -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
		
	</head>
	<body>

		<div class="container">
			
			<?php
				$data = array(
								'background' => array(
														'image' => 'fantasy-boy.jpg'
														),
								'layers' => array(
													array( 'type' => 'image', 'file' => 'twitter.png' ,'x' => 10, 'y' => 10, 'w' => 50, 'h' => 60, 'r' => 170, 'b' => 20, 't' => 0 ),
													array( 'type' => 'image', 'file' => 'facebook.png' ,'x' => 60, 'y' => 20, 'w' => 50, 'h' => 60, 'r' => 210, 'b' => 30, 't' => 0 ),
													array( 'type' => 'text', 'text' => 'hello world', 'font'=>'DJBTOOTSIEWOOTSIE.ttf', 'color'=>'red', 'stroke'=>'', 'x' => 160, 'y' => 20, 'w' => 50, 'h' => 60, 'r' => 50, 'b' => 30, 't' => 0 )
												)
								);
			?>

			<?php initiate_magickeditor(json_encode($data)); ?>
			
		</div>
	</body>
</html>
