<template>
  <div class="admin-base-multifile-upload">
    <FileUpload
      ref="fileUploader"
      name="files"
      :customUpload="true"
      :multiple="multiple"
      :accept="accept"
      chooseLabel="Browse"
      uploadLabel="Upload"
      :headers="headers"
      :disabled="uploading"
      @select="onSelect"
      @uploader="upload"
      class="w-full"
    >
      <template v-if="uploading" #header>
        <BaseSpinner />
      </template>
    </FileUpload>

    <p v-if="uploading" class="mt-2">Uploading…</p>
    <p v-if="error" class="mt-2 text-red-600">{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
  import { ref, watch } from 'vue';
  import FileUpload from 'primevue/fileupload';
  import type { PropType } from 'vue';
  import { useFileUpload, UseFileUploadOptions } from '@/composables/useFileUpload';
  import type { FileUploadEmitsOptions } from 'primevue/fileupload';

  const props = defineProps({
    url: { type: String, required: true },
    fieldName: { type: String, default: 'files' },
    headers: {
      type: Object as PropType<Record<string, string>>,
      default: () => ({})
    },
    multiple: { type: Boolean, default: true },
    accept: { type: String, default: '' },
    customData: {
      type: Object as PropType<Record<string, any>>,
      default: () => ({})
    }
  });

  const emit = defineEmits<{
    (e: 'uploaded', files: any[]): void;
  }>();

  const fileUploader = ref<FileUploadEmitsOptions | null>(null);

  const opts: UseFileUploadOptions = {
    url: props.url,
    fieldName: props.fieldName,
    multiple: props.multiple,
    extraData: () => props.customData,
    onCompleted: (files: GalleryItem[]) => {
      if (fileUploader.value) {
        fileUploader.value.clear();
      }
      emit('uploaded', files);
    }
  };

  const { uploading, error, onSelect, upload } = useFileUpload(opts);
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
