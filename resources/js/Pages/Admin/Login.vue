<template>
  <Head title="Login" />
  <main>
    <form @submit.prevent="submit">
      <Link class="login-logo" href="/">
        <img :src="'/images/logo.png'" alt="Zeke Bondy-Villa Invitational Golf Tournament logo." aria-hidden="true" />
      </Link>
      <div class="field">
        <label class="label">Email</label>
        <div class="control">
          <input class="input" type="email" v-model="form.email" placeholder="you@example.com" required autofocus />
        </div>
        <p class="help is-danger" v-if="form.errors.email">
          {{ form.errors.email }}
        </p>
      </div>
      <div class="field">
        <label class="label">Password</label>
        <div class="control">
          <input class="input" type="password" v-model="form.password" required autocomplete="current-password" />
        </div>
        <p class="help is-danger" v-if="form.errors.password">
          {{ form.errors.password }}
        </p>
      </div>
      <div class="field">
        <label class="checkbox">
          <input type="checkbox" v-model="form.remember" />
          Remember me
        </label>
      </div>
      <div class="field">
        <div class="control">
          <button class="button is-primary is-fullwidth" :disabled="form.processing">Login</button>
        </div>
      </div>
      <p class="has-text-centered">
        <a :href="route('password.request')">Forgot your password?</a>
      </p>
    </form>
  </main>
</template>

<script setup>
  import { useForm, Link } from '@inertiajs/vue3';
  import { Head } from '@inertiajs/vue3';

  const form = useForm({
    email: '',
    password: '',
    remember: false
  });

  function submit() {
    form.post(route('login'), {
      onFinish: () => form.reset('password')
    });
  }
</script>

<style lang="scss" scoped>
  main {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    height: 100dvh;
    background-color: #ccc;

    form {
      background-color: #fff;
      padding: 5rem;
      height: 100dvh;
      display: flex;
      flex-direction: column;
      justify-content: center;

      @include mixins.desktop {
        height: auto;
      }

      .login-logo {
        margin-bottom: 3rem;
      }
    }
  }
</style>
