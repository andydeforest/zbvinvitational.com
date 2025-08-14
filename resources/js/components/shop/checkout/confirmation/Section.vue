<template>
  <BaseSection class="shop-checkout-confirmation-section">
    <h1>Thank You for Your Order!</h1>
    <div class="columns is-3 is-mobile is-multiline">
      <div class="column is-full">
        <h2 class="title mb-4">Order Receipt</h2>
      </div>
      <div class="column is-full-mobile is-one-third-desktop shop-checkout-confirmation-section__group">
        <strong>Order Number:</strong>
        <span>
          <Link class="is-underlined" :href="`/shop/confirmation/${order.public_id}`">{{ order.public_id }}</Link>
        </span>
      </div>
      <div class="column is-full-mobile is-one-third-desktop shop-checkout-confirmation-section__group">
        <strong>Placed On:</strong>
        <span>{{ formattedDate(order.created_at) }}</span>
      </div>
      <div class="column is-full-mobile is-one-third-desktop shop-checkout-confirmation-section__group">
        <strong>Status:</strong>
        <div>
          <div
            class="shop-checkout-confirmation-section__badge"
            :class="order.status === 'paid' ? 'has-background-success' : 'has-background-warning'"
          >
            {{ capitalize(order.status) }}
          </div>
        </div>
      </div>
      <div class="column is-full">
        <table class="table is-fullwidth is-striped is-bordered">
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
        <div class="field is-grouped is-grouped-multiline is-justify-content-flex-end is-flex">
          <div class="control">
            <div class="tags has-addons">
              <span class="tag is-dark is-medium">Total</span>
              <span class="tag is-dark is-medium">${{ (order.total_cents / 100).toFixed(2) }}</span>
            </div>
          </div>
        </div>
        <div class="field">
          <p>
            We will have water stations placed throughout the course, please bring a personal water bottle to fill up as
            needed.
          </p>
          <p>
            If you have any questions about your order or technical difficulties, please
            <Link href="/contact" class="is-underlined">contact us.</Link>
          </p>
          <p>
            Please note our non-profit Tax ID #
            <strong>83-2803947</strong>
            for your records. Please consult your CPA for any potential deductions.
          </p>
        </div>
      </div>
    </div>
  </BaseSection>
</template>

<script setup lang="ts">
  import { Link } from '@inertiajs/vue3';

  const props = defineProps<{
    order: Order;
  }>();

  const order = reactive({ ...props.order });
  const polling = ref(false);
  let intervalHandle: ReturnType<typeof setInterval> | null = null;
  let timeoutHandle: ReturnType<typeof setTimeout> | null = null;

  onMounted(() => {
    if (order.status === 'pending') {
      polling.value = true;

      // poll for order update every 5 seconds or so
      intervalHandle = setInterval(async () => {
        const res = await fetch(`/api/orders/${order.public_id}`);
        if (!res.ok) return;
        const { data: fresh } = (await res.json()) as { data: Order };

        if (fresh.status !== order.status) {
          order.status = fresh.status;
        }
        if (order.status === 'paid') {
          clearInterval(intervalHandle!);
          clearTimeout(timeoutHandle!);
          polling.value = false;
        }
      }, 5000);

      // we give up after 30s
      timeoutHandle = setTimeout(() => {
        clearInterval(intervalHandle!);
        polling.value = false;
      }, 30 * 1000);
    }
  });

  onBeforeUnmount(() => {
    if (intervalHandle) clearInterval(intervalHandle);
    if (timeoutHandle) clearTimeout(timeoutHandle);
  });

  function formattedDate(iso: string): string {
    return new Date(iso).toLocaleString(undefined, {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    });
  }

  function capitalize(str: string): string {
    return str.charAt(0).toUpperCase() + str.slice(1);
  }
</script>

<style lang="scss">
  .shop-checkout-confirmation-section {
    display: flex;
    flex-direction: column;
    gap: 2rem;

    &__group {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    &__badge {
      display: inline-block;
      padding: 0 0.75rem;
      border-radius: 500px;
    }
  }
</style>
