<template>
  <AdminBaseSection title="Latest Orders" class="admin-dashboard-latest-orders-section">
    <table class="table is-striped is-fullwidth">
      <thead>
        <tr>
          <th>Date</th>
          <th>For</th>
          <th>Total</th>
          <th>Status</th>
          <th class="has-text-centered">Type</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <template v-if="orders.length">
          <tr v-for="(order, x) in orders" :key="`latest-order-${x}`">
            <td>{{ new Date(order.created_at).toLocaleDateString() }}</td>
            <td>{{ order.first_name[0] }}. {{ order.last_name }}</td>
            <td>${{ order.total_dollars.toFixed(2) }}</td>
            <td>{{ order.status }}</td>
            <td class="admin-dashboard-latest-orders-section__icon-grid">
              <span v-if="order.items.some((item) => item.product?.type === 'golf')" title="Golf">
                <LandPlot :size="iconSize" />
              </span>
              <span v-if="order.items.some((item) => item.product?.type === 'sponsorship')" title="Sponsorship">
                <Signpost :size="iconSize" />
              </span>
              <span v-if="order.items.some((item) => item.product?.type === 'donation')" title="Donation">
                <HandCoins :size="iconSize" />
              </span>
              <span v-if="order.items.some((item) => item.product?.type === 'dinner')" title="Dinner">
                <UtensilsCrossed :size="iconSize" />
              </span>
            </td>
            <td>
              <Link :href="`/admin/orders/${order.public_id}`">View</Link>
            </td>
          </tr>
        </template>
        <template v-else>
          <tr>
            <td colspan="6">No orders yet.</td>
          </tr>
        </template>
      </tbody>
    </table>
    <div class="admin-dashboard-latest-orders-section__footer">
      <Link href="/admin/orders" class="button is-link">View All</Link>
      <div class="admin-dashboard-latest-orders-section__legend">
        <div>
          <LandPlot :size="iconSize" />
          <span>Golf</span>
        </div>
        <div>
          <Signpost :size="iconSize" />
          <span>Sponsorship</span>
        </div>
        <div>
          <HandCoins :size="iconSize" />
          <span>Donation</span>
        </div>
        <div>
          <UtensilsCrossed :size="iconSize" />
          <span>Dinner</span>
        </div>
      </div>
    </div>
  </AdminBaseSection>
</template>

<script setup lang="ts">
  import axios from 'axios';
  import { Link } from '@inertiajs/vue3';
  import { LandPlot, Signpost, HandCoins, UtensilsCrossed } from 'lucide-vue-next';

  const iconSize = ref(20);
  const orders = ref<Order[]>([]);
  let refreshIntervalId: number;

  onMounted(async () => {
    orders.value = await fetchLatestOrders();

    // poll every 5 minutes
    refreshIntervalId = window.setInterval(
      async () => {
        orders.value = await fetchLatestOrders();
      },
      5 * 60 * 1000
    );
  });

  onUnmounted(() => {
    clearInterval(refreshIntervalId);
  });

  async function fetchLatestOrders(): Promise<Order[]> {
    const { data } = await axios.get<Order[]>('/api/orders?latest=10');
    return data;
  }
</script>

<style lang="scss">
  .admin-dashboard-latest-orders-section {
    &__icon-grid {
      display: flex;
      justify-content: center;
      gap: 2px;
    }

    &__footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    &__legend {
      display: flex;
      flex-direction: row;
      gap: 1.5rem;

      > div {
        display: flex;
        gap: 4px;
      }
    }
  }
</style>
