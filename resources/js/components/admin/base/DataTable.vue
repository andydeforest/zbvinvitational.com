<template>
  <div class="base-data-table">
    <div class="base-data-table__controls">
      <div>
        <div class="field mb-0">
          <label class="label is-small">Search</label>
          <input v-model="model.search" class="input is-small" placeholder="Type to filter" />
        </div>
      </div>
      <div class="base-data-table__controls--horizontal">
        <slot name="controls" :filters="filters" :model="model" />
      </div>
    </div>
    <ag-grid-vue
      class="ag-theme-alpine"
      style="width: 100%"
      :columnDefs="columnDefs"
      :rowData="filteredData"
      :defaultColDef="defaultColDef"
      @grid-ready="onGridReady"
      @row-selected="onRowSelect"
      domLayout="autoHeight"
      v-bind="gridOptions"
      :grid-options="gridOptions"
    />
    <slot name="details" :selected="selected" />
  </div>
</template>

<script setup lang="ts">
  import { AgGridVue } from 'ag-grid-vue3';
  import axios from 'axios';
  import type { GridOptions, GridReadyEvent } from 'ag-grid-community';

  interface FilterDef<T> {
    key: string;
    type: 'text' | 'select' | 'year';
    options?: any[];
    accessor?: (item: any) => any;
  }

  const gridOptions: GridOptions = {
    rowSelection: { mode: 'singleRow', checkboxes: false, enableClickSelection: true }
  };

  const props = defineProps<{
    sectionClass?: string;
    endpoint: string;
    columnDefs: any[];
    defaultColDef?: any;
    gridOptions?: Record<string, any>;
    filters?: FilterDef<any>[];
  }>();

  const emit = defineEmits<{
    (e: 'row-selected', data: any): void;
  }>();

  // internal state
  const rawData = ref<any[]>([]);
  const gridApi = ref<any>(null);
  const selected = ref<any>(null);

  const model = ref<Record<string, any>>({
    search: '',
    type: null,
    year: new Date().getFullYear(),
    status: 'paid'
  });

  function getByPath(obj: any, path: string): any {
    return path.split('.').reduce((o, key) => (o == null ? undefined : o[key]), obj);
  }

  function onRowSelect() {
    emit('row-selected', selected.value);
  }

  onMounted(async () => {
    const { data } = await axios.get(props.endpoint);
    rawData.value = data;
  });

  function onGridReady(e: GridReadyEvent) {
    gridApi.value = e.api;
  }

  const filteredData = computed(() => {
    return rawData.value.filter((item) => {
      // deep text search via JSON.stringify
      if (model.value.search) {
        const haystack = JSON.stringify(item).toLowerCase();
        if (!haystack.includes(model.value.search.toLowerCase())) return false;
      }

      return (props.filters || []).every((f) => {
        const v = model.value[f.key];
        if (v == null || v === '') return true;

        const rawVal = f.accessor ? f.accessor(item) : getByPath(item, f.key);

        switch (f.type) {
          case 'select':
            return rawVal === v;
          case 'year':
            return new Date(rawVal).getFullYear() === v;
        }
      });
    });
  });
</script>

<style lang="scss" scoped>
  .base-data-table {
    &__controls {
      display: flex;
      justify-content: space-between;
      margin-bottom: 1rem;

      &--horizontal {
        display: flex;
        gap: 1rem;
      }
    }
  }
</style>
