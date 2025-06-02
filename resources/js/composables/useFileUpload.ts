// composables/useFileUpload.ts
import { ref } from 'vue';
import axios from 'axios';

export interface GalleryItem {
  id: number;
  year: number;
  url: string;
  media: any;
}

export interface UseFileUploadOptions {
  url: string;
  fieldName?: string;
  multiple?: boolean;
  extraData?: () => Record<string, any> | Record<string, any>;
  batchSize?: number;
  concurrentBatches?: number;
  onCompleted?: Function;
}

function chunkArray<T>(arr: T[], size: number): T[][] {
  const chunks: T[][] = [];
  for (let i = 0; i < arr.length; i += size) {
    chunks.push(arr.slice(i, i + size));
  }
  return chunks;
}

export function useFileUpload(opts: UseFileUploadOptions) {
  const selected = ref<File[]>([]);
  const uploading = ref(false);
  const error = ref<string | null>(null);
  const uploaded = ref<GalleryItem[]>([]);

  const fieldName = opts.fieldName || 'files';
  const multiple = opts.multiple ?? true;
  const batchSize = opts.batchSize ?? 5;
  const concurrentBatches = opts.concurrentBatches ?? 1;

  const token = import.meta.env.VITE_ADMIN_TOKEN;
  const uploadHeaders = { Authorization: `Bearer ${token}` };

  function onSelect(evt: any) {
    selected.value = evt.files as File[];
    error.value = null;
  }

  async function uploadChunk(filesBatch: File[]): Promise<GalleryItem[]> {
    const form = new FormData();
    filesBatch.forEach((f) => form.append(`${fieldName}[]`, f));

    if (opts.extraData) {
      const dataObj = typeof opts.extraData === 'function' ? opts.extraData() : opts.extraData;
      Object.entries(dataObj).forEach(([key, value]) => {
        form.append(key, String(value));
      });
    }

    const res = await axios.post(opts.url, form, {
      headers: {
        'Content-Type': 'multipart/form-data',
        ...uploadHeaders
      }
    });

    return (res.data.data ?? res.data) as GalleryItem[];
  }

  async function upload() {
    if (!selected.value.length) {
      error.value = 'Please select at least one file.';
      return;
    }
    uploading.value = true;
    error.value = null;
    uploaded.value = [];

    const fileChunks = chunkArray(selected.value, batchSize);

    try {
      for (let i = 0; i < fileChunks.length; i += concurrentBatches) {
        const chunkGroup = fileChunks.slice(i, i + concurrentBatches);
        const groupPromises = chunkGroup.map((batch) => uploadChunk(batch));
        const groupResults = await Promise.all(groupPromises);

        groupResults.forEach((chunkResult) => {
          uploaded.value.push(...chunkResult);
        });
      }
      selected.value = [];
    } catch (e: any) {
      if (e.response?.data?.message) {
        error.value = e.response.data.message;
      } else if (e.response?.data?.errors) {
        error.value = JSON.stringify(e.response.data.errors);
      } else {
        error.value = e.message || 'Upload failed.';
      }
    } finally {
      if (typeof opts.onCompleted === 'function') {
        opts.onCompleted(uploaded.value);
      }
      uploading.value = false;
    }
  }

  return {
    selected,
    uploading,
    error,
    uploaded,
    multiple,
    onSelect,
    upload
  };
}
