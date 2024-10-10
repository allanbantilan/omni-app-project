<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Email</title>
</head>

<body>
  <h1>{{ $subject }}</h1>
  <p>{{ $content }}</p>

  @if (isset($attachment))
    <p>Attachment: {{ $attachment }}</p> <!-- You may want to adjust this -->
  @endif
</body>

</html>
