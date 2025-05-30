<template>
  <AdminBaseSection title="Event Settings" class="admin-dashboard-settings-section">
    <form @submit.prevent="submit">
      <div class="field is-horizontal">
        <label class="label">Event Date</label>
        <div class="control">
          <DatePicker
            v-model="dateModel"
            showIcon
            panelClass="box"
            dateFormat="MM dd, yy"
            :class="{ 'is-danger': form.errors.event_date }"
          />
          <p v-if="form.errors.event_date" class="my-0 help is-danger">{{ form.errors.event_date }}</p>
        </div>
      </div>
      <div class="field is-horizontal">
        <label class="label">Event Location Name</label>
        <div class="control">
          <input
            class="input"
            :class="{ 'is-danger': form.errors.event_location_name }"
            type="text"
            placeholder="Creekside Golf Course"
            v-model="form.event_location_name"
          />
          <p v-if="form.errors.event_location_name" class="my-0 help is-danger">
            {{ form.errors.event_location_name }}
          </p>
        </div>
      </div>
      <div class="field is-horizontal">
        <label class="label">Event Location Address</label>
        <div class="control">
          <input
            class="input"
            :class="{ 'is-danger': form.errors.event_location_address }"
            type="text"
            placeholder="701 Lincoln Ave, Modesto, CA 95354"
            v-model="form.event_location_address"
          />
          <p v-if="form.errors.event_location_address" class="my-0 help is-danger">
            {{ form.errors.event_location_address }}
          </p>
        </div>
      </div>
      <div class="field">
        <div class="control">
          <button type="submit" class="button is-primary" :class="{ 'is-loading': isSaving }">Save Settings</button>
        </div>
      </div>
    </form>
  </AdminBaseSection>
</template>

<script setup lang="ts">
  import { computed } from 'vue';
  import DatePicker from 'primevue/datepicker';
  import { useForm } from '@inertiajs/vue3';
  import { DateTime } from 'luxon';
  import { useEventInfo } from '@/composables/useEventInfo';

  const isSaving = ref(false);

  const { eventDate: rawEventDate, eventLocationName, eventLocationAddress } = useEventInfo();

  const form = useForm({
    event_date: rawEventDate.value?.toISO() ?? null,
    event_location_name: eventLocationName.value,
    event_location_address: eventLocationAddress.value
  });

  const dateModel = computed<Date | null>({
    get() {
      return form.event_date ? DateTime.fromISO(form.event_date, { setZone: true }).toJSDate() : null;
    },
    set(jsDate) {
      form.event_date = jsDate ? DateTime.fromJSDate(jsDate, { zone: 'America/Los_Angeles' }).toISO() : null;
    }
  });

  function submit() {
    isSaving.value = true;
    form.put('/admin/settings', {
      onSuccess: () => {
        isSaving.value = false;
      }
    });
  }
</script>

<style lang="scss">
  .admin-dashboard-settings-section {
    form {
      .field.is-horizontal {
        > * {
          display: flex;
          align-items: center;
          flex: 1;
        }

        .control {
          display: flex;
          flex-direction: column;

          > * {
            width: 100%;
          }
        }
        label {
          margin-bottom: 0;
        }
      }

      // custom styling for primevue datepicker
      .p-datepicker.is-danger {
        input,
        button {
          border-color: hsl(var(--bulma-danger-h), var(--bulma-danger-s), var(--bulma-danger-on-scheme-l));
        }
      }
    }
  }
</style>
