<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-110437717-1"></script>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="csrf-token" content="{{ csrf_token() }}">

		<link href="https://fonts.googleapis.com/css?family=Lato:300,300i,400,400i,700,700i,900" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBB-yLlqyCE6s_MA30UotaCHttwUw5nKNY&libraries=places"></script>

		<link href="{{ mix('/css/app.css' ) }}" rel="stylesheet" type="text/css"/>

		<link rel="icon" type="image/png" href="/favicon.png">

		<title>Roast and Brew - Helping the coffee enthusiast find their next cup of coffee</title>
		<meta name="description" content="Welcome to Roast and Brew! We help the coffee enthusiast find their next cup of coffee and teach developers how to develop apps!"/>
		<link rel="canonical" href="https://roastandbrew.coffee/" />
		<meta property="og:locale" content="en_US" />
		<meta property="og:type" content="website" />
		<meta property="og:title" content="Roast - Helping the coffee enthusiast find their next cup of coffee" />
		<meta property="og:url" content="https://roastandbrew.coffee/" />
		<meta property="og:site_name" content="Roast and Brew" />
		<meta property="og:image" content="https://roastandbrew.coffee/img/og-roast.jpg" />
		<meta property="og:image:secure_url" content="https://roastandbrew.coffee/img/og-roast.jpg" />
		<meta property="og:image:width" content="1200" />
		<meta property="og:image:height" content="630" />

		<meta name="twitter:card" content="summary_large_image" />
		<meta name="twitter:description" content="Welcome to Roast and Brew! We help the coffee enthusiast find their next cup of coffee and teach developers how to develop apps!" />
		<meta name="twitter:title" content="Roast - Helping the coffee enthusiast find their next cup of coffee" />
		<meta name="twitter:site" content="@roast_n_brew" />
		<meta name="twitter:image" content="https://roastandbrew.coffee/img/og-roast.jpg" />

		<script type='application/ld+json'>
			{
				"@context":"https:\/\/schema.org",
				"@type":"WebSite",
				"@id":"#website",
				"url":"https:\/\/roastandbrew.coffee\/",
				"name":"Roast And Brew"
			}
		</script>
		<script type='application/ld+json'>
			{
				"@context":"https:\/\/schema.org",
				"@type":"Organization",
				"url":"https:\/\/roastandbrew.coffee\/",
				"sameAs":
					[
						"https:\/\/www.facebook.com/roastandbrewcoffee\/",
						"https:\/\/twitter.com\/roast_n_brew"
					],
				"@id":"https:\/\/roastandbrew.coffee\/#organization",
				"name":"Roast And Brew",
				"logo":"https:\/\/roastandbrew.coffee\/img\/og-roast.jpg"
			}
		</script>
		<script type='text/javascript'>
			 window.Laravel = <?php echo json_encode([
				'csrfToken' => csrf_token(),
			]); ?>
		</script>
	</head>
	<body>
		<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

			ga('create', 'UA-118585310-1', 'auto');
			ga('send', 'pageview');
		</script>
		<div id="app">
			<router-view></router-view>
		</div>
		<script type="text/javascript" src="{{ mix('js/app.js') }}"></script>

	</body>
</html>
