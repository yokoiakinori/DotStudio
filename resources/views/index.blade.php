<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name') }}</title>

  <!-- Scripts -->
  <script src="{{ mix('js/app.js') }}" defer></script>

  <!-- Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Merriweather|Roboto:400">
  <link rel="stylesheet" href="https://unpkg.com/ionicons@4.2.2/dist/css/ionicons.min.css">
  <script src="https://kit.fontawesome.com/bc73f70475.js" crossorigin="anonymous"></script>

  <!-- Style -->
  <link rel="stylesheet" href="{{mix('css/common/common.css')}}"">
  
</head>
<body style="margin:0">
  <div id="app"></div>
</body>
</html>