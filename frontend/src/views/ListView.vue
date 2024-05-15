<template>
    <v-card flat elevation="2" class="w-100">
        <template v-slot:text>
            <v-text-field
                v-model="search"
                label="Search"
                prepend-inner-icon="mdi-magnify"
                variant="outlined"
                hide-details
            ></v-text-field>
        </template>

        <v-data-table
            id="table"
            :headers="headers"
            :items="files"
            :search="search"
            :loading="loading"
        >
            <template v-slot:item.status="{ value }">
                <v-chip :color="statuses[value]?.color">
                    {{ statuses[value]?.text }}
                </v-chip>
            </template>

            <template v-slot:item.execution="{ value }">
                {{ parseInt(value) }}ms
            </template>

            <template v-slot:item.created_at="{ value }">
                {{ fromISO8601(value) }}
            </template>
        </v-data-table>
    </v-card>
</template>
  
  <script>
import axios from 'axios'

export default {
    data() {
        return {
            search: '',
            headers: [
                {
                    align: 'start',
                    key: 'id',
                    sortable: false,
                    title: 'ID'
                },
                { key: 'original_name', title: 'Name' },
                { key: 'rows', title: 'Lines', align: 'center' },
                { key: 'status', title: 'Status', align: 'center' },
                { key: 'execution', title: 'Time spent', align: 'center' },
                { key: 'created_at', title: 'Date', align: 'center' }
            ],

            files: [],
            statuses: {
                0: { text: 'Processing...', color: 'warning' },
                1: { text: 'Error', color: 'error' },
                2: { text: 'Success', color: 'success' }
            },

            loading: false
        }
    },

    async mounted() {
        await this.getFiles()
    },

    methods: {
        async getFiles() {
            this.loading = true

            axios
                .get('http://localhost:8000/api/v1/file')
                .then((response) => {
                    this.files = response.data
                })
                .catch((error) => {
                    console.log(error)
                })
                .finally(() => {
                    this.loading = false
                })
        }
    }
}
</script>