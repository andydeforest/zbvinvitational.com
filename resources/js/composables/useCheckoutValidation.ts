import useVuelidate from '@vuelidate/core';
import { required, minLength, email } from '@vuelidate/validators';
import { reactive } from 'vue';

export function useCheckoutValidation() {
  const form = reactive({
    firstName: '',
    lastName: '',
    address: '',
    city: '',
    state: '',
    zip: '',
    phone: '',
    email: '',
    notes: ''
  });

  const rules = {
    firstName: { required },
    lastName: { required },
    address: { required, minLength: minLength(5) },
    city: { required },
    state: { required },
    zip: { required, minLength: minLength(5) },
    phone: { required, minLength: minLength(10) },
    email: { required, email }
  };

  const v$ = useVuelidate(rules, form);

  return { form, v$ };
}
