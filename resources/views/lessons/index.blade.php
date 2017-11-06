<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Document</title>
</head>
<body>
	<h1>Lessons</h1>

	<h4>In Progress</h4>
	<ul>
		@foreach ($inProgress as $lesson)
			<li>
				<a href="/lessons/{{ $lesson->id }}">{{ $lesson->title }}</a>
			</li>
		@endforeach
	</ul>

	<h4>All Lessons</h4>

	<ul>
		@foreach ($lessons as $lesson)
			<li>
				<a href="/lessons/{{ $lesson->id }}">{{ $lesson->title }}</a>
			</li>
		@endforeach
	</ul>
</body>
</html>
