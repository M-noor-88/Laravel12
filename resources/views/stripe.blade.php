<!DOCTYPE html>
<html>
<head>
    <title>Stripe Checkout</title>
    <script src="https://js.stripe.com/v3/"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #card-element {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
    </style>
</head>
<body class="p-5">
    <div class="container">
        <h2 id="donation-header">Donate</h2>

        <!-- Campaign Selector -->
        <div class="mb-4">
            <label for="campaign" class="form-label">Choose a Campaign:</label>
            <select id="campaign" class="form-select">
                <option selected disabled>Select a campaign</option>
                @foreach($campaigns as $camp)
                    <option
                        value="{{ $camp->id }}"
                        data-title="{{ $camp->title }}"
                        data-amount="{{ $camp->goal_amount }}"
                        data-client-secret="{{ $camp->client_secret }}"
                        data-stripe-key="{{ $camp->stripe_key }}"
                    >
                        {{ $camp->title }} (${{ number_format($camp->goal_amount, 2) }})
                    </option>
                @endforeach
            </select>
        </div>

        <form id="payment-form" style="display: none;">
            <div id="card-element" class="mb-3"></div>
            <button id="submit" class="btn btn-success">Pay</button>
            <div id="error-message" class="text-danger mt-2"></div>
        </form>
    </div>

    <script>
        let stripe, card;

        document.getElementById('campaign').addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            const title = selectedOption.getAttribute('data-title');
            const amount = selectedOption.getAttribute('data-amount');
            const clientSecret = selectedOption.getAttribute('data-client-secret');
            const stripeKey = selectedOption.getAttribute('data-stripe-key');

            document.getElementById('donation-header').textContent = `Donate $${parseFloat(amount).toFixed(2)} to ${title}`;
            document.getElementById('payment-form').style.display = 'block';

            // Initialize Stripe for the selected campaign
            stripe = Stripe(stripeKey);
            const elements = stripe.elements();
            if (card) {
                card.unmount();
            }
            card = elements.create('card');
            card.mount('#card-element');

            const form = document.getElementById('payment-form');
            form.onsubmit = async function (e) {
                e.preventDefault();

                const {error, paymentIntent} = await stripe.confirmCardPayment(clientSecret, {
                    payment_method: {
                        card: card,
                    }
                });

                if (error) {
                    document.getElementById('error-message').textContent = error.message;
                } else {
                    window.location.href = "/success";
                }
            };
        });
    </script>
</body>
</html>
