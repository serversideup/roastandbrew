<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Roast</title>

		<link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">

		<style>
			/* http://meyerweb.com/eric/tools/css/reset/
			 v2.0 | 20110126
			 License: none (public domain)
			*/

			html, body, div, span, applet, object, iframe,
			h1, h2, h3, h4, h5, h6, p, blockquote, pre,
			a, abbr, acronym, address, big, cite, code,
			del, dfn, em, img, ins, kbd, q, s, samp,
			small, strike, strong, sub, sup, tt, var,
			b, u, i, center,
			dl, dt, dd, ol, ul, li,
			fieldset, form, label, legend,
			table, caption, tbody, tfoot, thead, tr, th, td,
			article, aside, canvas, details, embed,
			figure, figcaption, footer, header, hgroup,
			menu, nav, output, ruby, section, summary,
			time, mark, audio, video {
				margin: 0;
				padding: 0;
				border: 0;
				font-size: 100%;
				font: inherit;
				vertical-align: baseline;
			}
			/* HTML5 display-role reset for older browsers */
			article, aside, details, figcaption, figure,
			footer, header, hgroup, menu, nav, section {
				display: block;
			}
			body {
				line-height: 1;
			}
			ol, ul {
				list-style: none;
			}
			blockquote, q {
				quotes: none;
			}
			blockquote:before, blockquote:after,
			q:before, q:after {
				content: '';
				content: none;
			}
			table {
				border-collapse: collapse;
				border-spacing: 0;
			}

			body{
				background-color: #fafafa;
			}

			span.logo{
				font-family: 'Josefin Sans', sans-serif;
				font-weight: bold;
				color: #7F5F2A;
				font-size: 40px;
				display: block;
				margin-top: 20px;
				margin-bottom: 20px;
				text-align: center;
			}

			div.login-box{
				max-width: 370px;
				min-width: 320px;
				margin: 30px auto 20px auto;
				padding: 0 10px;
				background-color: #fff;
		    border: 1px solid #ddd;
		    -webkit-box-shadow: 0 1px 3px rgba(50,50,50,0.08);
		    box-shadow: 0 1px 3px rgba(50,50,50,0.08);
		    -webkit-border-radius: 4px;
		    border-radius: 4px;
		    font-size: 16px;
			}

			div.login-box a{
				display: block;
				margin: auto;
				width: 230px;
				margin-top: 10px;
				margin-bottom: 10px;
			}
		</style>
	</head>
	<body>
		<span class="logo">Roast And Brew</span>

		<div class="login-box">
			<a href="/login/google">
				<img src="/img/google-login.svg"/>
			</a>

			<a href="/login/twitter">
				<img src="/img/twitter-login.svg"/>
			</a>

			<a href="/login/facebook">
				<img src="/img/facebook-login.svg"/>
			</a>
		</div>
	</body>
</html>
