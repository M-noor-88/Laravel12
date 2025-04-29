<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Donation Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-5">
    <div class="container text-center">
        <h2 class="text-success mb-4">Thank You!</h2>
        <p class="lead">Your donation was successfully processed.</p>
        <a href="{{ url('/campaigns') }}" class="btn btn-primary mt-3">Back to Campaigns</a>
    </div>
</body>
</html>
