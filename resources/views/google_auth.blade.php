<!DOCTYPE html>
<html>
<head>
    <title>Google Authentication</title>
</head>
<body>
    <h2>Hello,</h2>
    <p>Click the link below to authenticate with Google:</p>
    <a href="https://accounts.google.com/o/oauth2/auth?client_id={{ env('GOOGLE_CLIENT_ID') }}&redirect_uri={{ env('GOOGLE_REDIRECT_URI') }}&response_type=code&scope=email">Authenticate with Google</a>
</body>
</html>
