<template>
  <AdminBaseSection title="Golfers">
    <AdminBaseDataTable
      title="Golfer Registration"
      sectionClass="admin-golfers-list-section"
      endpoint="/api/golfers"
      :columnDefs="columnDefs"
      :filters="filters"
      @row-selected="openModal"
    >
      <template #controls="{ model }">
        <div class="field mb-0">
          <label class="label is-small">Year</label>
          <div class="select is-small">
            <select v-model.number="model.year">
              <option :value="null">All Years</option>
              <option v-for="y in years" :key="y" :value="y">{{ y }}</option>
            </select>
          </div>
        </div>
      </template>
    </AdminBaseDataTable>
    <AdminGolfersViewOrderModal :is-open="isModalOpen" :golfer="modalGolfer" @close="closeModal" />
  </AdminBaseSection>
</template>

<script setup lang="ts">
  import { DateTime } from 'luxon';
  import { ColDef } from 'ag-grid-community';

  const isModalOpen = ref(false);

  const years = computed(() => {
    const c = DateTime.local().year;
    return Array.from({ length: c - 2018 + 1 }, (_, i) => 2018 + i).reverse();
  });

  const filters = [
    { key: 'text', type: 'text' },
    {
      key: 'year',
      type: 'year',
      accessor: (item: any) => item.created_at
    }
  ];

  const columnDefs = ref<ColDef<Golfer>[]>([
    { headerName: 'Name', field: 'name', sortable: true, filter: true },
    { headerName: '# Holes', field: 'holes', maxWidth: 120 },
    { headerName: 'Email', field: 'order_item.order.email', sortable: true, filter: true },
    { headerName: 'Phone', field: 'order_item.order.phone' },
    { headerName: 'Notes', field: 'instructions', flex: 1 },
    {
      headerName: 'Registered',
      field: 'created_at',
      valueFormatter: (params: any) => {
        if (!params.value) return '';
        return DateTime.fromISO(params.value, { setZone: true })
          .setZone('America/Los_Angeles')
          .toLocaleString(DateTime.DATETIME_MED);
      },
      flex: 1
    }
  ]);

  const modalGolfer = ref<Golfer | null>(null);

  function openModal(golfer: Golfer) {
    modalGolfer.value = golfer;
    isModalOpen.value = true;
  }
  function closeModal() {
    isModalOpen.value = false;
    modalGolfer.value = null;
  }
</script>
