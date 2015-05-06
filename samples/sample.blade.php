<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Laravel PHP Framework</title>
	<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
</head>
<body>
<header>
	<nav class="navbar navbar-default navbar-static-top" role="nagivation">
		<div class="navbar-header">
			<a class="navbar-brand" href="/">Test Bootstrap</a>
		</div>
		<div class="collapse navbar-collapse in">

			<ul class="nav navbar-nav">

				{!! Bootstrap::navbar('index', 'bootstrap.index', 'Index') !!}

				{!! Bootstrap::navbarDropdown('links', 'Links',
				[
				'bootstrap.link1' => 'Link1',
				'bootstrap.link2' => 'Link2',
				'bootstrap.link3' => 'Link3',
				]
				)!!}

			</ul>

		</div>
	</nav>
</header>
<div class="col-md-4 col-md-offset-4">
	<div class="panel panel-default">
		<div class="panel-heading"><h3 class="panel-title">Sample</h3></div>
		<div class="panel-body">
			{!! Bootstrap::title(null) !!}
			{!! Bootstrap::password(null) !!}
			{!! Bootstrap::appendAttr(['class' => 'myclass'])->confirm_password(null) !!}
			{!! Bootstrap::required()->wysiwyg( 'description', 'Description', null, ['rows'=>5] ) !!}

			<div class="row">
				<div class="col-md-5">
					{!! Bootstrap::submit() !!}
					{!! Bootstrap::submit('My Own Title') !!}
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
</body>
</html>
