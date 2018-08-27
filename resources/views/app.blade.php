<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Direct App</title>
    <link rel="stylesheet" href="{{ mix('css/app.css')}} ">
    <script src="https://js.pusher.com/4.3/pusher.min.js"></script>
</head>
<body>
    <div id="app">
        <Index :user="{{ $user ?: 'null' }}"></Index>
    </div>
    <script src="{{ mix('js/app.js') }}"></script>
    <script>
        listenToSession('{{ config('sso.pusher-app-key') }}', {{ \App\Models\User::id() }})
    </script>
    @include('_logout')
</body>
</html>
