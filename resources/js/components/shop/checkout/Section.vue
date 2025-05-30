<template>
  <BaseSection class="shop-checkout-section">
    <template v-if="cart.items.length">
      <div class="columns is-multiline is-variable is-8">
        <div class="column is-two-thirds">
          <h2 class="mb-2">Billing Details</h2>
          <form class="shop-checkout-section__billing" @submit.prevent="handleSubmit" ref="billingForm">
            <div class="columns is-1">
              <div class="column">
                <div class="field">
                  <label class="label">First Name</label>
                  <div class="control">
                    <input
                      class="input"
                      :class="{ 'is-danger': v$.firstName.$error }"
                      v-model="form.firstName"
                      type="text"
                      placeholder="First Name"
                      @blur="v$.firstName.$touch()"
                      :disabled="billingInfoLocked"
                    />
                  </div>
                  <p v-if="v$.firstName.$error" class="help is-danger my-0">First name is required.</p>
                </div>
              </div>
              <div class="column">
                <div class="field">
                  <label class="label">Last Name</label>
                  <div class="control">
                    <input
                      class="input"
                      :class="{ 'is-danger': v$.lastName.$error }"
                      v-model="form.lastName"
                      type="text"
                      placeholder="Last Name"
                      @blur="v$.lastName.$touch()"
                      :disabled="billingInfoLocked"
                    />
                  </div>
                  <p v-if="v$.lastName.$error" class="help is-danger my-0">Last name is required.</p>
                </div>
              </div>
            </div>
            <div class="field">
              <label class="label">Address</label>
              <div class="control">
                <input
                  class="input"
                  @blur="v$.address.$touch()"
                  :class="{ 'is-danger': v$.address.$error }"
                  v-model="form.address"
                  type="text"
                  placeholder="Street Address"
                  :disabled="billingInfoLocked"
                />
              </div>
              <p v-if="v$.address.$error" class="help is-danger my-0">Address is required.</p>
            </div>
            <div class="columns is-1">
              <div class="column">
                <div class="field">
                  <label class="label">City</label>
                  <div class="control">
                    <input
                      :class="{ 'is-danger': v$.city.$error }"
                      @blur="v$.city.$touch()"
                      class="input"
                      v-model="form.city"
                      type="text"
                      placeholder="City"
                      :disabled="billingInfoLocked"
                    />
                  </div>
                  <p v-if="v$.city.$error" class="help is-danger my-0">City is required.</p>
                </div>
              </div>
              <div class="column">
                <div class="field">
                  <label class="label">State</label>
                  <div class="control">
                    <div class="select">
                      <select
                        v-model="form.state"
                        :class="{ 'is-danger': v$.state.$error }"
                        @blur="v$.state.$touch()"
                        :disabled="billingInfoLocked"
                      >
                        <option value="">Select</option>
                        <option value="AL">Alabama</option>
                        <option value="AK">Alaska</option>
                        <option value="AZ">Arizona</option>
                        <option value="AR">Arkansas</option>
                        <option value="CA">California</option>
                        <option value="CO">Colorado</option>
                        <option value="CT">Connecticut</option>
                        <option value="DE">Delaware</option>
                        <option value="DC">District Of Columbia</option>
                        <option value="FL">Florida</option>
                        <option value="GA">Georgia</option>
                        <option value="HI">Hawaii</option>
                        <option value="ID">Idaho</option>
                        <option value="IL">Illinois</option>
                        <option value="IN">Indiana</option>
                        <option value="IA">Iowa</option>
                        <option value="KS">Kansas</option>
                        <option value="KY">Kentucky</option>
                        <option value="LA">Louisiana</option>
                        <option value="ME">Maine</option>
                        <option value="MD">Maryland</option>
                        <option value="MA">Massachusetts</option>
                        <option value="MI">Michigan</option>
                        <option value="MN">Minnesota</option>
                        <option value="MS">Mississippi</option>
                        <option value="MO">Missouri</option>
                        <option value="MT">Montana</option>
                        <option value="NE">Nebraska</option>
                        <option value="NV">Nevada</option>
                        <option value="NH">New Hampshire</option>
                        <option value="NJ">New Jersey</option>
                        <option value="NM">New Mexico</option>
                        <option value="NY">New York</option>
                        <option value="NC">North Carolina</option>
                        <option value="ND">North Dakota</option>
                        <option value="OH">Ohio</option>
                        <option value="OK">Oklahoma</option>
                        <option value="OR">Oregon</option>
                        <option value="PA">Pennsylvania</option>
                        <option value="RI">Rhode Island</option>
                        <option value="SC">South Carolina</option>
                        <option value="SD">South Dakota</option>
                        <option value="TN">Tennessee</option>
                        <option value="TX">Texas</option>
                        <option value="UT">Utah</option>
                        <option value="VT">Vermont</option>
                        <option value="VA">Virginia</option>
                        <option value="WA">Washington</option>
                        <option value="WV">West Virginia</option>
                        <option value="WI">Wisconsin</option>
                        <option value="WY">Wyoming</option>
                      </select>
                    </div>
                  </div>
                  <p v-if="v$.state.$error" class="help is-danger my-0">State is required.</p>
                </div>
              </div>
              <div class="column">
                <div class="field">
                  <label class="label">Zip</label>
                  <div class="control">
                    <input
                      :class="{ 'is-danger': v$.zip.$error }"
                      @blur="v$.zip.$touch()"
                      class="input"
                      v-model="form.zip"
                      type="text"
                      placeholder="Zip"
                      :disabled="billingInfoLocked"
                    />
                  </div>
                  <p v-if="v$.zip.$error" class="help is-danger my-0">Zip is required.</p>
                </div>
              </div>
            </div>
            <div class="columns is-1">
              <div class="column">
                <div class="field">
                  <label class="label">Phone Number</label>
                  <div class="control">
                    <input
                      :class="{ 'is-danger': v$.phone.$error }"
                      @blur="v$.phone.$touch()"
                      class="input"
                      v-model="form.phone"
                      type="tel"
                      placeholder="Phone Number"
                      :disabled="billingInfoLocked"
                    />
                  </div>
                  <p v-if="v$.phone.$error" class="help is-danger my-0">Phone is required.</p>
                </div>
              </div>
              <div class="column">
                <div class="field">
                  <label class="label">E-mail</label>
                  <div class="control">
                    <input
                      :class="{ 'is-danger': v$.email.$error }"
                      @blur="v$.email.$touch()"
                      class="input"
                      v-model="form.email"
                      type="email"
                      placeholder="E-mail address"
                      :disabled="billingInfoLocked"
                    />
                  </div>
                  <p v-if="v$.email.$error" class="help is-danger my-0">E-mail is required.</p>
                </div>
              </div>
            </div>
            <template v-for="(item, x) in cart.items" :key="`optional-input-${x}`">
              <div
                v-if="item.product.checkout_form_schema?.length"
                class="columns"
                v-for="y in item.quantity"
                :key="`product-${x}-${y}`"
              >
                <div
                  class="column"
                  v-for="(field, z) in item.product.checkout_form_schema"
                  :key="`field-${z}`"
                  :class="field.containerClass"
                >
                  <label class="label">{{ field.label }} #{{ y }} ({{ item.product.short_name }})</label>
                  <div class="control">
                    <input
                      class="input"
                      :type="field.type"
                      v-model="metadataForm[getMetadataIndex(x, y)][field.key || field.label]"
                      :disabled="billingInfoLocked"
                      :required="field.required"
                    />
                  </div>
                </div>
              </div>
            </template>
            <div class="field">
              <label class="label">Order notes (optional)</label>
              <div class="control">
                <textarea
                  class="textarea"
                  v-model="form.notes"
                  placeholder="Notes about your order, etc."
                  :disabled="billingInfoLocked"
                ></textarea>
              </div>
            </div>
          </form>
        </div>
        <div class="column is-one-third">
          <div class="box" style="position: sticky; top: 2rem">
            <h2 class="mb-4">Payment Details</h2>
            <ShopCheckoutOrderSummary />
            <template v-if="step === 'billing'">
              <ShopCheckoutDisclaimer class="shop-checkout-section__disclaimer" />
              <div class="field mb-4">
                <label class="checkbox is-size-7">
                  <input type="checkbox" v-model="confirmWaiver" />
                  By checking this box, I confirm I am at least 21 years of age, and have read this release and waiver
                  liability agreement, fully understand its terms, understand that I will give up substantial rights by
                  signing it, and agree to it freely and voluntarily without any inducement.
                </label>
              </div>
            </template>
            <transition name="slide-down" mode="out-in">
              <form v-if="step === 'payment' && cardMounted" id="payment-form" class="box">
                <div id="card-element"></div>
                <div v-if="errorMessage" class="error-message">
                  <p class="has-text-danger">{{ errorMessage }}</p>
                </div>
              </form>
            </transition>
            <button
              :type="step === 'billing' ? 'button' : 'submit'"
              :class="{ 'is-loading': processing }"
              class="button is-black is-fullwidth is-medium mt-4"
              :disabled="step === 'billing' && (v$.$invalid || !confirmWaiver)"
              @click="step === 'billing' ? goToPayment() : handleSubmit()"
            >
              {{ step === 'billing' ? 'Go to Payment' : 'Submit Payment' }}
            </button>
            <button
              v-if="step === 'payment' && !processing"
              @click="cancelPayment"
              class="button is-fullwidth is-text is-small mt-5"
            >
              Or, Cancel
            </button>
          </div>
        </div>
      </div>
    </template>
  </BaseSection>
</template>

<script setup lang="ts">
  import { useCartStore } from '@/stores/cart';
  import { useStripePayment } from '@/composables/useStripePayment';
  import { useCheckoutValidation } from '@/composables/useCheckoutValidation';
  import { router } from '@inertiajs/vue3';
  import { nextTick } from 'vue';

  const cart = useCartStore();

  onMounted(() => {
    if (!cart.items.length) {
      router.visit('/shop');
    }
  });

  const { form, v$ } = useCheckoutValidation();

  const confirmWaiver = ref(false);
  const step = ref<'billing' | 'payment'>('billing');
  const cardMounted = ref(false);
  const billingInfoLocked = ref(false);

  const metadataForm = ref<any[]>([]);
  const billingForm = ref<HTMLFormElement | null>(null);

  watch(
    () => cart.items,
    (items) => {
      metadataForm.value = items.flatMap((item) => {
        return Array.from({ length: item.quantity }, (_, i) => ({
          product: item.product
        }));
      });
    },
    { immediate: true }
  );

  function getMetadataIndex(itemIndex: number, quantityIndex: number) {
    let index = 0;
    for (let i = 0; i < itemIndex; i++) {
      index += cart.items[i].quantity;
    }
    return index + (quantityIndex - 1);
  }

  // initialize stripe
  const {
    initElements,
    confirmPayment,
    errorMessage,
    processing,
    orderId,
    cardElement,
    elements,
    stripe,
    clientSecret
  } = useStripePayment(cart.items, form, metadataForm);

  async function goToPayment() {
    v$.value.$touch();
    if (v$.value.$invalid || !confirmWaiver.value) return;

    if (billingForm.value && !billingForm.value.checkValidity()) {
      // this will pop up the browser's native tooltips
      billingForm.value.reportValidity();
      return;
    }

    step.value = 'payment';
    billingInfoLocked.value = true;

    cardMounted.value = true;

    await nextTick();

    await initElements('#card-element');
  }

  async function cancelPayment() {
    if (orderId.value) {
      try {
        await fetch(`/api/orders/${orderId.value}`, {
          method: 'DELETE'
        });
      } catch (e) {
        console.error('Order deletion failed', e);
      }
    }

    if (cardElement.value) {
      cardElement.value.unmount();
      cardElement.value = null;
    }

    // reset elements
    elements.value = null;
    stripe.value = null;
    errorMessage.value = '';
    clientSecret.value = '';
    orderId.value = '';

    // reset UI
    confirmWaiver.value = false;
    v$.value.$reset();
    step.value = 'billing';
    billingInfoLocked.value = false;
    cardMounted.value = false;
  }

  async function handleSubmit() {
    const ok = await confirmPayment();
    if (ok) {
      cart.clearCart();

      router.get(
        `/shop/confirmation/${orderId.value}`,
        {},
        {
          preserveState: false,
          replace: true
        }
      );
    }
  }
</script>

<style lang="scss">
  .shop-checkout-section {
    &__billing {
      *:disabled {
        opacity: 0.75;
      }
    }

    &__disclaimer {
      max-height: 200px;
      margin-bottom: 1rem;
    }

    .select,
    .select > select {
      width: 100%;
    }
  }

  #payment-form {
    position: relative;
    margin-bottom: 50px;

    .error-message {
      position: absolute;
      bottom: -26px;
      height: 24px;
      font-size: 20px;
    }
  }

  .slide-down-enter-active {
    transition: all 0.5s ease-out;
  }
  .slide-down-enter-from {
    transform: translateY(-20px);
    opacity: 0;
  }
  .slide-down-enter-to {
    transform: translateY(0);
    opacity: 1;
  }
</style>
