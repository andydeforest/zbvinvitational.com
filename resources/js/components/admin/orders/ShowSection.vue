<template>
  <AdminBaseSection title="View Order" class="admin-orders-list-section">
    <div class="columns is-vcentered mb-4">
      <div class="column">
        <h2 class="title is-4">
          {{ order.public_id }}
        </h2>
        <span
          class="tag"
          :class="{
            'is-success': order.status === 'paid',
            'is-warning': order.status === 'pending',
            'is-danger': order.status === 'failed'
          }"
        >
          {{ order.status }}
        </span>
      </div>
      <div class="column has-text-right">
        <p>
          <strong>Placed:</strong>
          {{ formatDate(order.created_at) }}
        </p>
        <p v-if="order.paid_at">
          <strong>Paid:</strong>
          {{ formatDate(order.paid_at) }}
        </p>
      </div>
    </div>
    <div class="columns mb-4">
      <div class="column is-half">
        <p>
          <strong>Name:</strong>
          {{ order.first_name }} {{ order.last_name }}
        </p>
        <p>
          <strong>Email:</strong>
          <a :href="`mailto:${order.email}`">{{ order.email }}</a>
        </p>
        <p>
          <strong>Phone:</strong>
          {{ order.phone }}
        </p>
        <p>
          <strong>Stripe Payment ID:</strong>
          {{ order.stripe_payment_intent_id }}
        </p>
        <p>
          <strong>Stripe Charge ID:</strong>
          {{ order.stripe_charge_id }}
        </p>
      </div>
      <div class="column is-half">
        <p><strong>Address:</strong></p>
        <p>
          {{ order.address }}
          <br />
          {{ order.city }}, {{ order.state }} {{ order.zip }}
        </p>
      </div>
    </div>
    <div class="table-container mb-4">
      <table class="table is-striped is-fullwidth">
        <thead>
          <tr>
            <th>Product</th>
            <th class="has-text-centered">Qty</th>
            <th class="has-text-right">Unit Price</th>
            <th class="has-text-right">Line Total</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in order.items" :key="item.id">
            <td>{{ item.product?.display_name }}</td>
            <td class="has-text-centered">{{ item.quantity }}</td>
            <td class="has-text-right">${{ (item.unit_price_cents / 100).toFixed(2) }}</td>
            <td class="has-text-right">${{ ((item.unit_price_cents * item.quantity) / 100).toFixed(2) }}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="level mb-4">
      <div class="level-left"></div>
      <div class="level-right">
        <div>
          <p class="is-size-5">
            <strong>Total:</strong>
            ${{ order.total_dollars.toFixed(2) }}
          </p>
        </div>
      </div>
    </div>
    <div v-if="order.notes" class="notification is-info mb-4">
      <strong>Notes:</strong>
      <br />
      {{ order.notes }}
    </div>
    <div class="buttons">
      <Link href="/admin/orders" class="button is-light">← Back to Orders</Link>
    </div>
  </AdminBaseSection>
</template>

<script setup lang="ts">
  import { Link } from '@inertiajs/vue3';

  defineProps<{
    order: Order;
  }>();

  function formatDate(dateString: string) {
    return new Date(dateString).toLocaleString();
  }
</script>
