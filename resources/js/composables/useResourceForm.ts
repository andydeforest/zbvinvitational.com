import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

/**
 * Creates a reusable Inertia form using the Laravel resource pattern, whcih can handle create and update flows, including image uploads.
 *
 * @param {string} resource Resource name segment (i.e. Product, ProductCategory, etc) used in our RESTful urls.
 *   - POST   `/admin/${resource}` for create
 *   - PUT    `/admin/${resource}/${id}` for update
 *   - DELETE `/admin/${resource}/${id}` for delete
 * @param {Partial<T>} initialData Optional initial form values. If `initialData.id` is truthy, the form will be in edit mode. Otherwise, it will be in create mode.
 * @returns {
 *   - form: UseForm<T>;
 *   - submit: () => void;
 *   - isEdit: ComputedRef<boolean>;
 * }
 */
export function useResourceForm<T extends { id?: number }>(resource: string, initialData?: Partial<T>) {
  const form = useForm<T>({ ...(initialData || {}) } as T);
  const isEdit = computed(() => !!(initialData && (initialData as any).id));

  // strip out stale url strings
  form.transform((data) => {
    const d = data as any;

    if (typeof d.cover_image === 'string') {
      delete d.cover_image;
    }

    return d;
  });

  // helper to see if the user actually attached a new file
  const hasNewCoverFile = () => (form as any).cover_image instanceof File;

  function submit() {
    const url = isEdit.value ? `/admin/${resource}/${(form as any).id}` : `/admin/${resource}`;

    const onSuccess = !isEdit.value ? () => form.reset() : undefined;

    // if we’re editing AND there’s a real File, force a POST + method‐override
    if (isEdit.value && hasNewCoverFile()) {
      form.post(url, {
        onSuccess,
        headers: { 'X-HTTP-Method-Override': 'PUT' }
      });
    } else {
      // otherwise use the normal JSON methods
      const method = isEdit.value ? 'put' : 'post';
      form[method](url, { onSuccess });
    }
  }

  return { form, submit, isEdit };
}
