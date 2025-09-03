<template>
  <AdminBaseSection title="Orders">
    <AdminBaseDataTable
      sectionClass="admin-orders-list-section"
      endpoint="/api/order-items"
      :columnDefs="columnDefs"
      :filters="filters"
    >
      <template #controls="{ filters, model }">
        <div class="field mb-0">
          <label class="label is-small">Order Status</label>
          <div class="select is-small">
            <select v-model="model.status">
              <option :value="null">All</option>
              <option value="paid">Paid</option>
              <option value="pending">Pending</option>
            </select>
          </div>
        </div>
        <div class="field mb-0">
          <label class="label is-small">Order Type</label>
          <div class="select is-small">
            <select v-model="model.type">
              <option :value="null">All</option>
              <option v-for="t in productTypes" :key="t" :value="t">{{ uppercaseFirst(t) }}</option>
            </select>
          </div>
        </div>
        <div class="field mb-0">
          <label class="label is-small">Year</label>
          <div class="select is-small">
            <select v-model.number="model.year">
              <option :value="null">All Years</option>
              <option v-for="y in years" :key="y" :value="y">
                {{ y }}
              </option>
            </select>
          </div>
        </div>
      </template>
    </AdminBaseDataTable>
  </AdminBaseSection>
</template>

<script setup lang="ts">
  import { DateTime } from 'luxon';
  import Link from '@/components/admin/orders/Link.vue';
  import { ColDef } from 'ag-grid-community';

  function uppercaseFirst(str: string) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
  }

  defineProps<{ productTypes: string[] }>();

  const years = computed(() => {
    const c = DateTime.local().year;
    return Array.from({ length: c - 2018 + 1 }, (_, i) => 2018 + i).reverse();
  });

  const filters = [
    {
      key: 'status',
      type: 'select',
      accessor: (item: any) => item.order.status
    },
    {
      key: 'type',
      type: 'select',
      accessor: (item: any) => item.product?.type
    },
    {
      key: 'year',
      type: 'year',
      accessor: (item: any) => item.created_at
    }
  ];

  const columnDefs = ref<ColDef<OrderItem>[]>([
    {
      headerName: 'Order ID',
      field: 'order.public_id',
      sortable: false,
      filter: true,
      cellRendererSelector: (params) => {
        return {
          component: Link,
          params: { values: [params.value] }
        };
      }
    },
    {
      headerName: 'Name',
      filter: true,
      valueGetter: (params) =>
        params.data?.order ? `${params.data.order.first_name} ${params.data.order.last_name}` : ''
    },
    {
      headerName: 'Email',
      field: 'order.email',
      sortable: true,
      filter: true,
      cellRenderer: (params: any) => {
        const email = params.value || '';
        return `<a href="mailto:${email}">${email}</a>`;
      }
    },
    { headerName: 'Item', field: 'product.short_name' },
    {
      headerName: 'Item',
      field: 'product.type',
      cellRenderer: (params: any) => {
        return uppercaseFirst(params.value);
      }
    },
    {
      headerName: 'Price',
      field: 'unit_price_cents',
      cellRenderer: (params: any) => {
        return `$${(Number.parseInt(params.value) / 100).toFixed(2)}`;
      },
      flex: 1
    },
    {
      headerName: 'Order Placed',
      filter: true,
      field: 'created_at',
      valueFormatter: (params: any) => {
        if (!params.value) return '';
        return DateTime.fromISO(params.value, { setZone: true })
          .setZone('America/Los_Angeles')
          .toLocaleString(DateTime.DATETIME_MED);
      }
    },
    {
      headerName: 'Status',
      field: 'order.status',
      cellRenderer: (params: any) => {
        return uppercaseFirst(params.value);
      },
      flex: 1
    }
  ]);
</script>
