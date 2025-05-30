<template>
  <div>
    <AdminBaseSection title="Contact Messages" class="admin-contact-inbox-section">
      <div class="admin-contact-inbox-section__container">
        <div class="admin-contact-inbox-section__list" ref="listContainer">
          <ul>
            <li v-for="(message, x) in messages" :key="`message-list-${x}`" :data-id="message.id">
              <button
                @click="handleMessageSelection(message)"
                class="button is-fullwidth admin-contact-inbox-section__button is-size-7"
                :class="{ 'is-active': message.id === selectedMessage?.id }"
              >
                <div>
                  <div>{{ message.name }}</div>
                  <div class="has-text-grey-light">{{ formatDate(message.created_at) }}</div>
                </div>
                <p class="is-size-7 has-text-grey-light my-0">{{ message.message }}</p>
              </button>
            </li>
          </ul>
        </div>
        <div class="admin-contact-inbox-section__message">
          <template v-if="selectedMessage">
            <div class="admin-contact-inbox-section__message-header">
              <div>
                <strong>
                  {{ selectedMessage.name }}
                  <br />
                  <a :href="`mailto:${selectedMessage.email}`">{{ selectedMessage.email }}</a>
                </strong>
                <small class="has-text-grey-light">
                  {{ formatDate(selectedMessage.created_at) }}
                </small>
              </div>
              <div>
                <span class="tag is-small" :class="selectedMessage.type === 'support' ? 'is-info' : 'is-success'">
                  {{ selectedMessage.type }}
                </span>
              </div>
            </div>
            <hr class="my-0" />
            <div>
              <p>
                {{ selectedMessage.message }}
              </p>
            </div>
          </template>
          <template v-else>
            <p>No message selected</p>
          </template>
        </div>
      </div>
    </AdminBaseSection>
  </div>
</template>

<script setup lang="ts">
  import axios from 'axios';
  import { DateTime } from 'luxon';

  const messages = ref<Contact[] | null>(null);
  const selectedMessage = ref<Contact | null>(null);
  const listContainer = ref<HTMLElement | null>(null);

  onMounted(async () => {
    messages.value = await fetchLatestMessages();

    // check for hash
    const hash = window.location.hash;
    if (hash.startsWith('#')) {
      const id = parseInt(hash.slice(1), 10);
      if (!isNaN(id) && messages.value) {
        const msg = messages.value.find((m) => m.id === id);
        if (msg) {
          selectedMessage.value = msg;
          scrollToMessage(id);
        }
      }
    }
  });

  async function fetchLatestMessages(): Promise<Contact[]> {
    const { data } = await axios.get<Contact[]>('/api/contact');
    return data;
  }

  function handleMessageSelection(message: Contact) {
    selectedMessage.value = message;
    scrollToMessage(message.id);
  }

  function scrollToMessage(id: number) {
    nextTick(() => {
      if (!listContainer.value) return;
      const li = listContainer.value.querySelector<HTMLLIElement>(`li[data-id="${id}"]`);
      if (li) {
        li.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    });
  }

  const formatDate = (iso: string) =>
    DateTime.fromISO(iso).setZone('America/Los_Angeles').toLocaleString(DateTime.DATE_MED);
</script>

<style lang="scss">
  .admin-contact-inbox-section {
    &__container {
      display: flex;
      gap: 3rem;
    }

    &__list {
      max-height: 50dvh;
      overflow-y: scroll;
      width: 300px;

      ul {
        li:not(:first-child):not(:last-child) {
          .button,
          .button.is-active {
            border-radius: 0;
          }
        }

        li:not(:last-child) {
          .button {
            border-bottom: 0;
          }
        }

        li:first-child {
          .button {
            border-bottom-right-radius: 0;
            border-bottom-left-radius: 0;
          }
        }

        li:last-child {
          .button {
            border-top-right-radius: 0;
            border-top-left-radius: 0;
          }
        }
      }
    }

    &__message {
      display: flex;
      flex-direction: column;
      gap: 1rem;
      flex: 1;
      min-width: 0;

      p {
        white-space: pre-wrap;
        overflow-wrap: break-word;
        word-break: break-word;
      }
    }

    &__message-header {
      display: flex;
      flex-direction: column;
      gap: 1rem;

      > div {
        display: flex;
        justify-content: space-between;
      }
    }

    &__button {
      text-align: left;
      display: flex;
      flex-direction: column;
      justify-content: space-between;

      p {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: block;
        width: 100%;
      }

      > * {
        display: flex;
        width: 100%;

        flex-direction: row;
        justify-content: space-between;
      }
    }
  }
</style>
