<template>
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="grid grid-cols-3 md:grid-cols-6 xl:grid-cols-8 2xl:grid-cols-10 4xl:grid-cols-12 gap-5 items-center justify-between">
            <div v-for="image in props.product.images" :key="image.id" class="group">
                <div
                    class="w-24 h-24 relative group shadow-md hover:bg-red-500 hover:border-2 hover:border-red-500 rounded-md border border-gray-300">
                    <img
                        :class="{'border-4 border-green-500': image.main === true}"
                        :src="image.full" class="absolute inset-0 z-0 w-24 h-24 object-cover filter group-hover:opacity-25 group-hover:contrast-200 group-hover:grayscale rounded-md">
                    <div
                        @click.prevent="deleteImage(image)"
                        class="opacity-0 hover:opacity-100 duration-300 absolute inset-0 z-10 flex justify-center items-center text-sm text-white font-semibold cursor-pointer">delete</div>
                </div>
                <div
                    @click="setAsMain(image)"
                    class="opacity-0 group-hover:opacity-100 text-xs group-hover:text-indigo-600 font-semibold text-black/50 text-center cursor-pointer mt-2">
                    set as main
                </div>
            </div>
        </div>
        <div ref="dropRef" class="dropzone custom-dropzone mt-5"></div>
        <div class="dropzone preview-container"></div>
    </div>
    <ConfirmationModal ref="confirmationModal" @confirm="doDelete"/>
</template>

<script setup>

import {onMounted, ref, computed, watch} from "vue";
import { Dropzone } from "dropzone";
import ConfirmationModal from "@/Components/Modals/ConfirmationModal";

const confirmationModal = ref(null);

const props = defineProps({
    product: {
        type: Object,
        required: true
    }
});

const dropRef = ref(null)
const customPreview = `
      <div class="dz-preview dz-processing dz-image-preview dz-complete">
        <div class="dz-image">
          <img data-dz-thumbnail>
        </div>
        <div class="dz-details">
          <div class="dz-size"><span data-dz-size></span></div>
            <div class="dz-filename"><span data-dz-name></span></div>
          </div>
          <div class="dz-progress">
            <span class="dz-upload" data-dz-uploadprogress></span>
          </div>
          <div class="dz-error-message"><span data-dz-errormessage></span></div>
          <div class="dz-success-mark">
            <svg width="54" height="54" viewBox="0 0 54 54" fill="white" xmlns="http://www.w3.org/2000/svg">
              <path d="M10.2071 29.7929L14.2929 25.7071C14.6834 25.3166 15.3166 25.3166 15.7071 25.7071L21.2929 31.2929C21.6834 31.6834 22.3166 31.6834 22.7071 31.2929L38.2929 15.7071C38.6834 15.3166 39.3166 15.3166 39.7071 15.7071L43.7929 19.7929C44.1834 20.1834 44.1834 20.8166 43.7929 21.2071L22.7071 42.2929C22.3166 42.6834 21.6834 42.6834 21.2929 42.2929L10.2071 31.2071C9.81658 30.8166 9.81658 30.1834 10.2071 29.7929Z" />
            </svg>
          </div>
          <div class="dz-error-mark">
            <svg width="54" height="54" viewBox="0 0 54 54" fill="white" xmlns="http://www.w3.org/2000/svg">
              <path d="M26.2929 20.2929L19.2071 13.2071C18.8166 12.8166 18.1834 12.8166 17.7929 13.2071L13.2071 17.7929C12.8166 18.1834 12.8166 18.8166 13.2071 19.2071L20.2929 26.2929C20.6834 26.6834 20.6834 27.3166 20.2929 27.7071L13.2071 34.7929C12.8166 35.1834 12.8166 35.8166 13.2071 36.2071L17.7929 40.7929C18.1834 41.1834 18.8166 41.1834 19.2071 40.7929L26.2929 33.7071C26.6834 33.3166 27.3166 33.3166 27.7071 33.7071L34.7929 40.7929C35.1834 41.1834 35.8166 41.1834 36.2071 40.7929L40.7929 36.2071C41.1834 35.8166 41.1834 35.1834 40.7929 34.7929L33.7071 27.7071C33.3166 27.3166 33.3166 26.6834 33.7071 26.2929L40.7929 19.2071C41.1834 18.8166 41.1834 18.1834 40.7929 17.7929L36.2071 13.2071C35.8166 12.8166 35.1834 12.8166 34.7929 13.2071L27.7071 20.2929C27.3166 20.6834 26.6834 20.6834 26.2929 20.2929Z" />
          </svg>
        </div>
      </div>
    `

const token = computed(() => document.cookie.split('; ').find(row => row.startsWith('XSRF-TOKEN')).split('=')[1])

function doDelete(image) {
    axios.delete(route('admin.products.images.delete', {product_id: props.product.id, image_id: image.id})).then((response) => {
        if(response.status === 200) {
            props.product.images = props.product.images.filter(i => i.id !== image.id);
        }
    });
}

function deleteImage(image) {
    if(confirmationModal.value) {
        confirmationModal.value.open = true
        confirmationModal.value.image = image
        confirmationModal.value.modalConfig = {
            title: 'Delete Image',
            body: 'Are you sure you want to delete this image?',
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
        }
    }
}

function setAsMain(image) {
    axios.post(route('admin.products.images.set-main', {product_id: props.product.id, image_id: image.id})).then((response) => {
        if(response.status === 200) {
            props.product.images.forEach(i => i.main = false);
            image.main = true;
        }
    });
}

onMounted(() => {
    if(dropRef.value !== null) {
        new Dropzone(dropRef.value, {
            previewTemplate: customPreview,
            url: route('admin.products.images.store', props.product.id),
            method: 'POST',
            paramName: 'image',
            previewsContainer: dropRef.value.parentElement.querySelector('.preview-container'),
            maxFilesize: 4,
            parallelUploads: 2,
            headers: {
                "X-XSRF-TOKEN": decodeURIComponent(token.value)
            },
            init: function () {
                this.on('drop', function (file, xhr, formData) {
                    getToken()
                })

                this.on('success', function (file, response) {
                    if(response.images !== undefined) {
                        props.product.images = response.images
                    }
                })
            },
        })

        if(dropRef.value.querySelector('.dz-default')) {
            dropRef.value.querySelector('.dz-default').innerHTML = `
<label for="cover-photo" class="block text-sm font-medium text-gray-700">Cover photo</label>
            <div class="mt-1 flex justify-center rounded-md border-2 border-dashed border-gray-300 px-6 pt-5 pb-6">
              <div class="space-y-1 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                  <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="flex text-sm text-gray-600">
                  <label for="file-upload" class="relative cursor-pointer rounded-md bg-white font-medium text-indigo-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:text-indigo-500">
                    <span>Upload a file</span>
                    <input id="file-upload" name="file-upload" type="file" class="sr-only" />
                  </label>
                  <p class="pl-1">or drag and drop</p>
                </div>
                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                <button class="dz-button p-2 border-2 border-zinc-500 rounded-md mt-5" type="button">or select files</button>
              </div>
            </div>
          `
        }
    }
})

async function getToken() {
    await axios.get('/sanctum/csrf-cookie')
}
</script>
