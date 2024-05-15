<template>
    <v-card
        flat
        elevation="2"
        class="w-100 pa-5 fill-height d-flex flex-column justify-space-between"
    >
        <div class="d-flex flex-column w-100 mb-10">
            <div
                class="dropzone d-flex flex-column justify-center align-center pa-2 w-100"
                @dragover.prevent
                @drop="onDrop"
                @click="pickFile('fileInput')"
            >
                <v-file-input
                    id="file"
                    ref="fileInput"
                    v-model="file"
                    accept="text/csv"
                    style="display: none"
                    @change="selectFile()"
                />
                <v-icon class="mb-3 text-h3" icon="mdi-file-upload-outline" />

                <span
                    class="w-100 text-center fw-bold text-muted"
                    v-if="file !== null"
                >
                    {{ file.name }} ({{ showFileSize(file.size) }})
                </span>

                <span class="w-100 text-center fw-bold text-muted" v-else>
                    Drag & drop or select a file
                </span>
            </div>
            <div class="d-flex justify-space-between w-100 mt-3">
                <small> Supported formats: {{ validTypes.join(', ') }} </small>
                <small> Maximum size: {{ maximumSize }}MB </small>
            </div>
        </div>

        <div class="mb-10">
            <v-card variant="tonal" class="pa-10">
                <div class="d-flex flex-row justify-space-between align-center">
                    <div class="d-flex flex-column">
                        <div class="d-flex align-center mb-3">
                            <v-icon
                                icon="mdi-microsoft-excel"
                                class="me-2"
                            ></v-icon>
                            <span>Table example</span>
                        </div>
                        <small>
                            You can download the attached example and use them
                            as a starting point for your own file.
                        </small>
                    </div>
                    <div>
                        <v-btn
                            variant="tonal"
                            rounded="0"
                            class="p-5 flex-grow-1"
                            prepend-icon="mdi-clipboard-text-outline"
                            href="/example.csv"
                        >
                            Download
                        </v-btn>
                    </div>
                </div>
            </v-card>
        </div>

        <div class="d-flex w-100">
            <v-btn
                variant="tonal"
                elevation="2"
                rounded="0"
                class="flex-grow-1"
                prepend-icon="mdi-clipboard-text-outline"
                :disabled="file == null"
                @click="uploadFile()"
                :loading="uploading"
            >
                Upload
            </v-btn>
        </div>
    </v-card>
</template>
  
  <script>
import axios from 'axios'
import JSZip from 'jszip'

export default {
    data() {
        return {
            file: null,
            uploading: false,
            validTypes: ['text/csv'],
            maximumSize: 1
        }
    },
    methods: {
        onDrop(event) {
            event.preventDefault()

            const file = event.dataTransfer.files[0]

            this.validateFile(file)
        },

        pickFile(ref) {
            this.$refs[ref].click()
        },

        async selectFile() {
            let file = this.file
            this.file = null

            this.validateFile(file)
        },

        validateFile(file) {
            if (file.size > this.maximumSize * 1000000) {
                this.toast(
                    'error',
                    `The maximum size is ${this.maximumSize}MB.`,
                    3000
                )
                return
            }

            if (
                !(
                    file &&
                    this.validTypes.some((word) => file.type.startsWith(word))
                )
            ) {
                this.toast(
                    'error',
                    `Only the ${this.validTypes.join(
                        ', '
                    )} format are supported.`,
                    3000
                )
                return
            }

            this.file = file
        },

        showFileSize(size) {
            if (size < 1024) return size + ' B'
            if (size < 1024 * 1024) return (size / 1024).toFixed(2) + ' KB'
            return (size / (1024 * 1024)).toFixed(2) + ' MB'
        },

        async uploadFile() {
            this.uploading = true

            const zip = new JSZip()
            const form = new FormData()
            const self = this

            form.append('file_name', this.file.name)

            const zippedFile = zip.file(this.file.name, this.file)

            this.toast('info', 'Compressing file...', 3000)

            await zippedFile
                .generateAsync({ type: 'blob', compression: 'DEFLATE' })
                .then(function (blob) {
                    console.log(self.file.size, blob.size)
                    const compressed = 100 - (blob.size * 100) / self.file.size
                    self.toast(
                        'success',
                        `File compressed (-${parseInt(compressed)}%)`,
                        3000
                    )

                    form.append('file_upload', blob)
                })

            this.toast('info', 'Sending files...', 3000)

            axios.post('http://localhost:8000/api/v1/file', form, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })

            this.toast('success', 'File sent to be processed.', 3000)
            this.uploading = false
        }
    }
}
</script>

<style scoped>
.dropzone {
    border: 1px dashed
        rgba(var(--v-theme-on-surface), var(--v-high-emphasis-opacity));
    border-radius: 4px;
    height: 300px;
    width: 100%;
    cursor: pointer;
    font-size: 12px;
    transition: 0.2s;
    background-color: rgba(var(--v-theme-on-surface), 0.1);
}

.dropzone:hover {
    opacity: calc(
        var(--v-medium-emphasis-opacity) * var(--v-theme-overlay-multiplier)
    );
}
</style>