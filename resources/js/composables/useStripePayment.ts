import { ref } from 'vue';
import { loadStripe, Stripe, StripeElements, StripeCardElement } from '@stripe/stripe-js';

export function useStripePayment(cartItems: any[], billingDetails: any, checkoutMetadata: Ref<any[]>) {
  const stripe = ref<Stripe | null>(null);
  const elements = ref<StripeElements | null>(null);
  const cardElement = ref<StripeCardElement | null>(null);

  const clientSecret = ref<string>('');
  const errorMessage = ref<string>('');
  const processing = ref<boolean>(false);
  const orderId = ref<string>('');

  const stripePromise = loadStripe(import.meta.env.VITE_STRIPE_PUBLIC_KEY);

  async function fetchPaymentIntent() {
    const res = await fetch('/api/payment-intent', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ cart: cartItems, billing: billingDetails, metadata: checkoutMetadata.value })
    });
    if (!res.ok) throw new Error(res.statusText);

    const data = await res.json();

    clientSecret.value = data.clientSecret;
    orderId.value = data.orderId;
  }

  async function initElements(domSelector = '#card-element') {
    if (!clientSecret.value) {
      await fetchPaymentIntent();
    }
    stripe.value = await stripePromise;
    if (!stripe.value) throw new Error('Stripe.js failed to load');

    elements.value = stripe.value.elements();
    cardElement.value = elements.value.create('card');
    cardElement.value.mount(domSelector);

    cardElement.value.on('change', (e) => {
      errorMessage.value = e.error?.message ?? '';
    });
  }

  async function confirmPayment() {
    if (!stripe.value || !cardElement.value || !clientSecret.value) {
      throw new Error('Payment not initialized');
    }
    processing.value = true;

    const { firstName, lastName, address, city, state, zip, email, phone } = billingDetails;

    // stipe only accepts particular parameters, and ill kick back requests with extraneous values
    const stripeBillingDetails = {
      name: `${firstName} ${lastName}`,
      email,
      phone,
      address: {
        line1: address,
        city,
        state,
        postal_code: zip
      }
    };

    const { error, paymentIntent } = await stripe.value.confirmCardPayment(clientSecret.value, {
      payment_method: {
        card: cardElement.value,
        billing_details: stripeBillingDetails
      }
    });

    processing.value = false;

    if (error) {
      errorMessage.value = error.message || 'Payment failed';
      return false;
    }

    return paymentIntent?.status === 'succeeded';
  }

  return {
    initElements,
    confirmPayment,
    errorMessage,
    processing,
    orderId,
    cardElement,
    elements,
    stripe,
    clientSecret,
    useStripePayment
  };
}
