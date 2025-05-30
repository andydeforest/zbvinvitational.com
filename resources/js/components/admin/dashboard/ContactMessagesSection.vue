<template>
  <AdminBaseSection title="Contact Messages">
    <table class="table is-striped is-fullwidth">
      <thead>
        <tr>
          <th>Date</th>
          <th>From</th>
          <th>Type</th>
          <th>Preview</th>
        </tr>
      </thead>
      <tbody>
        <template v-if="messages?.length">
          <tr v-for="(message, x) in messages" :key="`latest-contact-${x}`">
            <td>{{ new Date(message.created_at).toLocaleDateString() }}</td>
            <td>{{ message.name }}</td>
            <td>{{ message.type }}</td>
            <td class="truncated">
              <Link :href="`/admin/contact#${message.id}`">{{ message.message }}</Link>
            </td>
          </tr>
        </template>
        <template v-else>
          <tr>
            <td colspan="5">No messages yet.</td>
          </tr>
        </template>
      </tbody>
    </table>
  </AdminBaseSection>
</template>

<script setup lang="ts">
  import axios from 'axios';
  import { Link } from '@inertiajs/vue3';

  const messages = ref<Contact[] | null>(null);

  onMounted(async () => {
    messages.value = await fetchLatestMessages();
  });

  async function fetchLatestMessages(): Promise<Contact[]> {
    const { data } = await axios.get<Contact[]>('/api/contact?latest=10');
    return data;
  }
</script>

<style scoped lang="scss">
  .table {
    .truncated {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 200px;
    }
  }
</style>
