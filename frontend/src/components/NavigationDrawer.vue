<template>
    <v-navigation-drawer elevation="2" permanent expand-on-hover rail>
        <v-list>
            <v-list-item
                prepend-avatar="https://media.licdn.com/dms/image/D4D03AQEhYsJVSaSEpA/profile-displayphoto-shrink_200_200/0/1699283241460?e=1720656000&v=beta&t=fGHgiXkEWjQUQkkFvcpR78_qTQcsQh8FP2TcLS-52oQ"
                subtitle="thomas.ricardo.reinke@gmail.com"
                title="Thomas Ricardo Reinke"
            ></v-list-item>
        </v-list>

        <v-divider></v-divider>

        <v-list density="compact" nav>
            <v-list-item
                prepend-icon="mdi-folder-outline"
                title="My files"
                :to="{ name: 'upload.list.view' }"
            ></v-list-item>
            <v-list-item
                prepend-icon="mdi-cloud-upload-outline"
                title="New upload"
                :to="{ name: 'upload.view' }"
            ></v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
import router from '@/router'

export default {
    name: 'NavigatiorDrawerComponent',
    data() {
        return {}
    },
    methods: {
        goTo(routeName) {
            router.push({ name: routeName })
        }
    }
}
</script>
