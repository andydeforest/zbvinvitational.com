<template>
  <div class="modal admin-golfers-view-order-modal" :class="{ 'is-active': isOpen }">
    <div class="modal-background"></div>
    <div v-if="golfer" class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Golfer Details</p>
        <button @click="close" class="delete" aria-label="close"></button>
      </header>
      <section class="modal-card-body">
        <div class="columns is-multiline">
          <div class="column is-half">
            <h4 class="title is-5">Golfer</h4>
            <div class="content">
              <p>
                <strong>Name:</strong>
                {{ golfer.name }}
              </p>
              <p>
                <strong>Holes:</strong>
                {{ golfer.holes }}
              </p>
            </div>
          </div>
          <div class="column is-half">
            <h4 class="title is-5">Order Item</h4>
            <div class="content">
              <p>
                <strong>Quantity:</strong>
                {{ golfer.order_item.quantity }}
              </p>
              <p>
                <strong>Unit Price:</strong>
                ${{ (golfer.order_item.unit_price_cents / 100).toFixed(2) }}
              </p>
              <p>
                <strong>Subtotal:</strong>
                ${{ ((golfer.order_item.unit_price_cents * golfer.order_item.quantity) / 100).toFixed(2) }}
              </p>
            </div>
          </div>
          <div class="column is-full">
            <p v-if="golfer.instructions">
              <strong>Instructions:</strong>
              {{ golfer.instructions }}
            </p>
            <p>
              <strong>Registered At:</strong>
              {{ formatted(golfer.created_at) }}
            </p>
          </div>
          <div class="column is-full">
            <h4 class="title is-5">Billing Information</h4>
            <div class="columns">
              <div class="column is-half content">
                <p>
                  <strong>Order ID:</strong>
                  {{ golfer.order_item.order.public_id }}
                </p>
                <p>
                  <strong>Status:</strong>
                  {{ golfer.order_item.order.status }}
                </p>
                <p>
                  <strong>Total:</strong>
                  ${{ (golfer.order_item.order.total_cents / 100).toFixed(2) }}
                </p>
              </div>
              <div class="column is-half content">
                <p>
                  <strong>Name:</strong>
                  {{ golfer.order_item.order.first_name }}
                  {{ golfer.order_item.order.last_name }}
                </p>
                <p>
                  <strong>Email:</strong>
                  <a :href="`mailto:${golfer.order_item.order.email}`">
                    {{ golfer.order_item.order.email }}
                  </a>
                </p>
                <p>
                  <strong>Phone:</strong>
                  {{ golfer.order_item.order.phone }}
                </p>
                <p>
                  <strong>Address:</strong>
                  {{ golfer.order_item.order.address }}, {{ golfer.order_item.order.city }},
                  {{ golfer.order_item.order.state }}
                  {{ golfer.order_item.order.zip }}
                </p>
              </div>
            </div>
            <p v-if="golfer.order_item.order.notes">
              <strong>Notes:</strong>
              {{ golfer.order_item.order.notes }}
            </p>
          </div>
          <div class="column is-full">
            <p>
              <strong>Stripe ID:</strong>
              {{ golfer.order_item.order.stripe_payment_intent_id }}
            </p>
          </div>
          <div class="column is-full">
            <a
              :href="`/shop/confirmation/${golfer.order_item.order.public_id}`"
              class="button is-link is-outlined is-fullwidth"
              target="_blank"
            >
              View Receipt
            </a>
          </div>
        </div>
      </section>
      <footer class="modal-card-foot">
        <div class="buttons">
          <button @click="close" class="button">Close</button>
        </div>
      </footer>
    </div>
  </div>
</template>

<script setup lang="ts">
  import { DateTime } from 'luxon';

  defineProps<{
    isOpen: boolean;
    golfer: Golfer;
  }>();

  const emit = defineEmits<{
    (e: 'close', value: boolean): void;
  }>();

  function formatted(iso: string) {
    return DateTime.fromISO(iso, { setZone: true })
      .setZone('America/Los_Angeles')
      .toLocaleString(DateTime.DATETIME_MED);
  }

  function close() {
    emit('close', false);
  }
</script>

<style lang="scss">
  .admin-golfers-view-order-modal {
    .modal-card-body {
      p {
        margin-bottom: 0.5rem;
      }
    }
  }
</style>
