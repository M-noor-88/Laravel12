<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Campaigns</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Choose a Campaign to Donate</h2>
        <ul class="list-group">
            @foreach($campaigns as $campaign)
                <li class="list-group-item">
                    <a href="{{ route('checkout', $campaign->id) }}">
                        {{ $campaign->title }} - Goal: ${{ number_format($campaign->goal_amount, 2) }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</body>
</html>
