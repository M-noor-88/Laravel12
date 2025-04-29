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
        <h2>Donate ${{ number_format($amount, 2) }} to {{ $campaign->title }}</h2>

        <form id="payment-form">
            <div id="card-element" class="mb-3"></div>
            <button id="submit" class="btn btn-success">Pay</button>
            <div id="error-message" class="text-danger mt-2"></div>
        </form>
    </div>

    <script>
        const stripe = Stripe("{{ $stripeKey }}");
        const elements = stripe.elements();
        const card = elements.create('card');
        card.mount('#card-element');

        const form = document.getElementById('payment-form');
        const clientSecret = "{{ $clientSecret }}";

        form.addEventListener('submit', async (e) => {
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
        });
    </script>
</body>
</html>
