
<div class="container">
    <h2>Donate to {{ $campaign->title }}</h2>

    <input type="number" id="donation-amount" placeholder="Enter amount" class="form-control mb-3" />
    <div id="card-element" class="mb-3"></div>
    <button id="submit-button" class="btn btn-primary">Donate</button>

    <div id="payment-message" class="mt-3"></div>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe("{{ $stripeKey }}");

    const elements = stripe.elements();
    const cardElement = elements.create('card');
    cardElement.mount('#card-element');

    document.getElementById('submit-button').addEventListener('click', async () => {
        const amount = document.getElementById('donation-amount').value;
        const response = await fetch("{{ route('donation.intent', $campaign->id) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ amount: amount })
        });

        const data = await response.json();

        const result = await stripe.confirmCardPayment(data.clientSecret, {
            payment_method: {
                card: cardElement,
            }
        });

        if (result.error) {
            document.getElementById('payment-message').innerText = result.error.message;
        } else {
            if (result.paymentIntent.status === 'succeeded') {
                document.getElementById('payment-message').innerText = "Donation successful! 🎉";
            }
        }
    });
</script>
