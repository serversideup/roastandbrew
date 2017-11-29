<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-110437717-1"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());

		  gtag('config', 'UA-110437717-1');
		</script>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<link href="https://fonts.googleapis.com/css?family=Lato:300,300i,400,400i,700,700i" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBB-yLlqyCE6s_MA30UotaCHttwUw5nKNY"></script>

		<link href="/css/app.css" rel="stylesheet" type="text/css"/>

		<link rel="icon" type="image/x-icon" href="/favicon.ico">

		<title>Roast</title>

		<script type='text/javascript'>
			 window.Laravel = <?php echo json_encode([
				'csrfToken' => csrf_token(),
			]); ?>
		</script>
	</head>
	<body>

		<div id="app">
			<router-view></router-view>
		</div>

		<script type="text/javascript" src="js/app.js"></script>

	</body>
</html>
