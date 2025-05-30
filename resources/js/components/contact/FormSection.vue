<template>
  <BaseSection class="contact-form-section">
    <div>
      <h2>Contact Form</h2>
      <div v-if="$page.props.flash.success" class="notification is-success my-2">
        {{ $page.props.flash.success }}
      </div>
      <form @submit.prevent="onSubmit">
        <div class="field">
          <label class="label" for="name">Name</label>
          <div class="control">
            <input
              id="name"
              v-model="form.name"
              class="input"
              :class="{ 'is-danger': form.errors.name }"
              type="text"
              placeholder="Your name"
              required
            />
          </div>
          <p v-if="form.errors.name" class="help is-danger">{{ form.errors.name }}</p>
        </div>
        <div class="field">
          <label class="label" for="email">Email</label>
          <div class="control">
            <input
              id="email"
              v-model="form.email"
              class="input"
              :class="{ 'is-danger': form.errors.email }"
              type="email"
              placeholder="you@example.com"
              required
            />
          </div>
          <p v-if="form.errors.email" class="help is-danger">{{ form.errors.email }}</p>
        </div>
        <div class="field">
          <label class="label" for="email">Inquiry Type</label>
          <div class="control">
            <div class="select">
              <select v-model="form.type">
                <option value="general">Question/Comment/Concern</option>
                <option value="volunteering/donating">Volunteering/Donating</option>
                <option value="order">Order Inquiry</option>
                <option value="other">Other</option>
              </select>
            </div>
          </div>
          <p v-if="form.errors.type" class="help is-danger">{{ form.errors.type }}</p>
        </div>
        <div v-if="form.type === 'order'" class="field">
          <label class="label" for="orderNumber">Order # (optional)</label>
          <div class="control">
            <input
              id="orderNumber"
              v-model="form.orderNumber"
              class="input"
              :class="{ 'is-danger': form.errors.orderNumber }"
              type="orderNumber"
            />
          </div>
          <p v-if="form.errors.type" class="help is-danger">{{ form.errors.type }}</p>
        </div>
        <div class="field">
          <label class="label" for="message">Message</label>
          <div class="control">
            <textarea
              id="message"
              v-model="form.message"
              class="textarea"
              :class="{ 'is-danger': form.errors.message }"
              rows="5"
              required
            ></textarea>
          </div>
          <p v-if="form.errors.message" class="help is-danger">{{ form.errors.message }}</p>
        </div>
        <div class="field">
          <div class="control">
            <button
              type="submit"
              class="button is-primary"
              :class="{ 'is-loading': form.processing }"
              :disabled="form.processing"
            >
              Send Message
            </button>
          </div>
        </div>
      </form>
    </div>
    <div>
      <h2>Direct Contacts</h2>
      <ul class="contact-form-section__direct">
        <li>
          <span>Lynn Bondy-Villa</span>
          <a class="is-underlined" href="tel:+12094994076">(209) 499-4076</a>
        </li>
        <li>
          <span>Kevin McCormick</span>
          <a class="is-underlined" href="tel:+19168043084">(916) 804-3084</a>
        </li>
        <li>
          <span>Mike Virga</span>
          <a class="is-underlined" href="tel:+19167699958">(916) 769-9958</a>
        </li>
        <li>
          <span>Adam Bondy-Villa</span>
          <a class="is-underlined" href="tel:+12093388904">(209) 338-8904</a>
        </li>
        <li>
          <span>Austin Bondy-Villa</span>
          <a class="is-underlined" href="tel:+12093388258">(209) 338-8258</a>
        </li>
      </ul>
    </div>
  </BaseSection>
</template>

<script setup lang="ts">
  import { useForm } from '@inertiajs/vue3';

  const form = useForm({
    name: '',
    email: '',
    type: 'general',
    orderNumber: '',
    message: ''
  });

  function onSubmit() {
    form.post('/contact', {
      onSuccess: () => {
        form.reset();
      }
    });
  }
</script>

<style lang="scss">
  .contact-form-section {
    display: flex;
    flex-direction: column;
    gap: 1rem;

    @include mixins.desktop {
      flex-direction: row;
      gap: 3rem;

      > *:first-of-type {
        flex: 1;
      }
    }

    h2 {
      margin-bottom: 1rem;
    }

    &__direct {
      display: flex;
      flex-direction: column;
      gap: 1rem;

      li {
        display: flex;
        flex-direction: column;

        span {
          font-family: var(--heading-font-family);
          font-size: 1.5rem;
          font-weight: 600;
        }
      }
    }
  }
</style>
