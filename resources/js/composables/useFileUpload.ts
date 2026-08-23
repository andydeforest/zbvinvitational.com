// composables/useFileUpload.ts
import { ref } from 'vue';
import axios from 'axios';

export interface UploadedResource {
  id: number;
  [key: string]: unknown;
}

export interface UseFileUploadOptions {
  url: string;
  fieldName?: string;
  multiple?: boolean;
  extraData?: () => Record<string, any> | Record<string, any>;
  headers?: Record<string, string>;
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

function normalizeUploadError(error: any): string {
  const validationErrors = error.response?.data?.errors;

  if (validationErrors && typeof validationErrors === 'object') {
    const firstError = Object.values(validationErrors)
      .flat()
      .find((message) => typeof message === 'string');

    if (typeof firstError === 'string') {
      return firstError;
    }
  }

  if (typeof error.response?.data?.message === 'string') {
    return error.response.data.message;
  }

  return error.message || 'Upload failed.';
}

function isUploadedResource(item: unknown): item is UploadedResource {
  if (!item || typeof item !== 'object') {
    return false;
  }

  const candidate = item as Partial<UploadedResource>;

  return typeof candidate.id === 'number';
}

function parseUploadResponse(response: any): UploadedResource[] {
  const contentType = response.headers?.['content-type'];

  if (typeof contentType === 'string' && contentType.includes('text/html')) {
    const status = response.status;

    if (status === 401 || status === 419) {
      throw new Error('Upload session expired. Please refresh the page and sign in again.');
    }

    if (status >= 500) {
      throw new Error('Upload failed on the server. Please try again in a moment.');
    }

    throw new Error(`Upload failed (HTTP ${status}).`);
  }

  const payload = response.data?.data ?? response.data;

  if (!Array.isArray(payload) || !payload.every(isUploadedResource)) {
    throw new Error('Unexpected upload response from the server.');
  }

  return payload;
}

export function useFileUpload(opts: UseFileUploadOptions) {
  const selected = ref<File[]>([]);
  const uploading = ref(false);
  const error = ref<string | null>(null);
  const uploaded = ref<UploadedResource[]>([]);

  const fieldName = opts.fieldName || 'files';
  const multiple = opts.multiple ?? true;
  const batchSize = opts.batchSize ?? 5;
  const concurrentBatches = opts.concurrentBatches ?? 1;

  const token = import.meta.env.VITE_ADMIN_TOKEN;

  const uploadHeaders = {
    ...(opts.headers ?? {}),
    ...(token ? { Authorization: `Bearer ${token}` } : {})
  };

  function onSelect(evt: any) {
    selected.value = evt.files as File[];
    error.value = null;
  }

  async function uploadChunk(filesBatch: File[]): Promise<UploadedResource[]> {
    const form = new FormData();
    filesBatch.forEach((f) => form.append(`${fieldName}[]`, f));

    if (opts.extraData) {
      const dataObj = typeof opts.extraData === 'function' ? opts.extraData() : opts.extraData;
      Object.entries(dataObj).forEach(([key, value]) => {
        form.append(key, String(value));
      });
    }

    const res = await axios.post(opts.url, form, {
      withCredentials: true,
      headers: {
        Accept: 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        ...uploadHeaders
      }
    });

    return parseUploadResponse(res);
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

      if (typeof opts.onCompleted === 'function') {
        opts.onCompleted(uploaded.value);
      }
    } catch (e: any) {
      error.value = normalizeUploadError(e);
    } finally {
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
