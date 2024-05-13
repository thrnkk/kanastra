<template>
    <v-card flat class="w-100 pa-5 fill-height">
        <div
            class="dropzone d-flex flex-column justify-center align-center pa-2"
            @dragover.prevent
            @drop="onDrop"
            @click="pickFile('image')"
        >
            <v-file-input
                id="file"
                ref="image"
                v-model="selectedImage"
                accept="image/png, image/jpg"
                style="display: none"
                @change="selectFile('image')"
            />
            <v-icon class="blue-font fw-bold mb-3" icon="mdi-plus" />

            <span class="w-100 text-center fw-bold text-muted">
                Arraste uma imagem ou selecione um arquivo
            </span>
        </div>
    </v-card>
</template>
  
  <script>
export default {
    data() {
        return {}
    },
    methods: {
        onDrop(event) {
            event.preventDefault()

            const file = event.dataTransfer.files[0]
            const type = file.type.split('/')[0]
            let validTypes = []

            if (type == 'image') {
                validTypes = ['image/png', 'image/jpeg', 'image/jpg']
            }

            if (type == 'video') {
                validTypes = ['video/mp4', 'video/mkv']
            }

            this.uploadFile(file, validTypes, type)
        },

        pickFile(ref) {
            this.$refs[ref].click()
        },

        uploadFile(file, validTypes, type) {
            this.imagesToAdd.push(file)

            if (file && validTypes.some((word) => file.type.startsWith(word))) {
                if (type == 'image')
                    this.images.push({
                        name: file.name,
                        url: URL.createObjectURL(file)
                    })
                if (type == 'video')
                    this.videos.push({
                        name: file.name,
                        url: URL.createObjectURL(file)
                    })

                return
            }

            this.$root.toast(
                'error',
                'Somente os formatos .jpg e .png são suportados'
            )
        }
    }
}
</script>

<style scoped>
.dropzone {
    border: 1px dashed #206bb5;
    border-radius: 4px;
    height: 100%;
    width: 100%;
    cursor: pointer;
    font-size: 12px;
    transition: 0.2s;
}

.dropzone:hover {
    opacity: calc(
        var(--v-medium-emphasis-opacity) * var(--v-theme-overlay-multiplier)
    );
}
</style>