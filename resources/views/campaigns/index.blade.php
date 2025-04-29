<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Campaigns</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .campaign-card {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 12px;
            transition: transform 0.2s;
        }
        .campaign-card:hover {
            transform: translateY(-5px);
        }
        .donate-btn {
            width: 100%;
        }
    </style>
</head>
<body class="bg-light">
<div class="container py-5">
    <h2 class="mb-4 text-center">Support a Campaign</h2>
    <div class="row">
        @foreach($campaigns as $campaign)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card campaign-card p-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $campaign->title }}</h5>
                        <p class="card-text text-muted">{{ $campaign->description }}</p>

                        <p>
                            <strong>Goal:</strong> ${{ number_format($campaign->goal_amount, 2) }}<br>
                            <strong>Raised:</strong> ${{ number_format($campaign->current_amount, 2) }}
                        </p>

                        <form method="POST" action="{{ url('/donate/checkout') }}">
                            @csrf
                            <input type="hidden" name="campaign_id" value="{{ $campaign->id }}">
                            <div class="mb-3">
                                <label for="amount-{{ $campaign->id }}" class="form-label">Enter Amount (USD):</label>
                                <input type="number" name="amount" min="1" step="0.01" required class="form-control" id="amount-{{ $campaign->id }}">
                            </div>
                            <button type="submit" class="btn btn-primary">Donate</button>
                        </form>

                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
</body>
</html>
