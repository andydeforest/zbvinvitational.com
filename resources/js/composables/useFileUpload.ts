import { ref } from 'vue';
import axios from 'axios';

export interface UseFileUploadOptions {
  url: string;
  fieldName?: string;
  multiple?: boolean;
}

const token = import.meta.env.VITE_ADMIN_TOKEN;
const uploadHeaders = { Authorization: `Bearer ${token}` };

export function useFileUpload(opts: UseFileUploadOptions) {
  const selected = ref<File[]>([]);
  const uploading = ref(false);
  const error = ref<string | null>(null);
  const uploaded = ref<any[]>([]);

  const fieldName = opts.fieldName || 'files';
  const multiple = opts.multiple ?? true;

  function onSelect(evt: any) {
    selected.value = evt.files as File[];
    error.value = null;
  }

  async function upload() {
    if (!selected.value.length) {
      error.value = 'Please select at least one file.';
      return;
    }
    uploading.value = true;
    error.value = null;

    const form = new FormData();
    selected.value.forEach((f) => form.append(`${fieldName}[]`, f));

    try {
      const res = await axios.post(opts.url, form, {
        headers: { 'Content-Type': 'multipart/form-data', ...uploadHeaders }
      });
      uploaded.value = res.data.data ?? res.data;
      selected.value = [];
    } catch (e: any) {
      error.value = e.response?.data?.message || e.message || 'Upload failed.';
    } finally {
      uploading.value = false;
    }
  }

  return { selected, uploading, error, uploaded, multiple, onSelect, upload };
}
