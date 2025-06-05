<template>
  <AdminBaseSection class="admin-dashboard-statistics-section" title="Statistics">
    <div class="admin-dashboard-statistics-section__grid">
      <div>
        <p class="heading">Orders ({{ year }})</p>
        <p class="title">{{ stats?.yearly.orders }}</p>
      </div>
      <div>
        <p class="heading">Golfers ({{ year }})</p>
        <p class="title">{{ stats?.yearly.golfers }}</p>
      </div>
      <div>
        <p class="heading">Donations ({{ year }})</p>
        <p class="title">${{ ((stats?.yearly.donationRevenue ?? 0) / 100).toFixed(2) }}</p>
      </div>
      <div>
        <p class="heading">Hole Sponsors ({{ year }})</p>
        <p class="title">{{ stats?.yearly.sponsorships }}</p>
      </div>
      <div>
        <p class="heading">Orders (Last 7 days)</p>
        <p class="title">{{ stats?.yearly.orders }}</p>
      </div>
      <div>
        <p class="heading">Golfers (Last 7 days)</p>
        <p class="title">{{ stats?.yearly.golfers }}</p>
      </div>

      <div>
        <p class="heading">Donations (Last 7 days)</p>
        <p class="title">${{ ((stats?.yearly.donationRevenue ?? 0) / 100).toFixed(2) }}</p>
      </div>
      <div>
        <p class="heading">Hole Sponsors (Last 7 days)</p>
        <p class="title">{{ stats?.yearly.sponsorships }}</p>
      </div>
    </div>
  </AdminBaseSection>
</template>

<script setup lang="ts">
  import axios from 'axios';
  import { Link } from '@inertiajs/vue3';
  import { MoveRight } from 'lucide-vue-next';

  interface Metrics {
    donationRevenue: number;
    golfers: number;
    orders: number;
    sponsorships: number;
  }

  interface Statistics {
    weekly: Metrics;
    yearly: Metrics;
  }

  const year = new Date().getFullYear();
  const stats = ref<Statistics | null>(null);

  onMounted(async () => {
    stats.value = await fetchStatistics();
  });

  async function fetchStatistics(): Promise<Statistics> {
    const { data } = await axios.get<Statistics>('/api/statistics');
    console.log(data);
    return data;
  }
</script>

<style lang="scss">
  .admin-dashboard-statistics-section {
    &__grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 2rem;

      > div {
        display: flex;
        flex-direction: column;
      }

      .heading {
        margin-bottom: 0;
      }

      .title {
        margin-bottom: 0;
        color: var(--zbv-primary);
      }
    }
  }
</style>
