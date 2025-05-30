<template>
  <div class="admin-base-multifile-upload">
    <FileUpload
      ref="fileUploader"
      name="files"
      :customUpload="true"
      :multiple="multiple"
      :accept="accept"
      chooseLabel="Browse…"
      uploadLabel="Upload"
      :headers="headers"
      :disabled="uploading"
      @select="onSelect"
      @uploader="upload"
      class="w-full"
    />

    <p v-if="uploading" class="mt-2">Uploading…</p>
    <p v-if="error" class="mt-2 text-red-600">{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
  import { toRefs, ref, watch } from 'vue';
  import FileUpload from 'primevue/fileupload';
  import type { PropType } from 'vue';
  import type { FileUploadEmitsOptions } from 'primevue/fileupload';
  import { useFileUpload, UseFileUploadOptions } from '@/composables/useFileUpload';

  const fileUploader = ref<FileUploadEmitsOptions | null>(null);

  // define our props
  const props = defineProps({
    url: { type: String, required: true },
    fieldName: { type: String, default: 'files' },
    headers: { type: Object as PropType<Record<string, string>>, default: () => ({}) },
    multiple: { type: Boolean, default: true },
    accept: { type: String, default: '' }
  });

  // allow parent to listen for 'uploaded'
  const emit = defineEmits<{
    (e: 'uploaded', files: any[]): void;
  }>();

  // pull out refs
  const { url, fieldName, headers, multiple, accept } = toRefs(props);

  // wire up the composable
  const { selected, uploading, error, uploaded, onSelect, upload } = useFileUpload({
    url: url.value,
    fieldName: fieldName.value,
    multiple: multiple.value
  });

  // ref to the PrimeVue FileUpload so we can clear its queue
  const uploader = ref<InstanceType<typeof FileUpload> | null>(null);

  function clearAll() {
    if (fileUploader.value) {
      fileUploader.value.clear();
    }
  }

  // whenever `uploaded` changes, emit to parent
  watch(uploaded, (files) => {
    if (files.length) {
      clearAll();
      emit('uploaded', files);
    }
  });
</script>

<style lang="scss">
  .admin-base-multifile-upload {
    .p-fileupload-advanced {
      .p-fileupload-header {
        display: flex;
        flex-direction: column;

        @include mixins.tablet {
          flex-direction: row;
        }
      }
    }
  }
</style>
