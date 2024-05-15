<template>
    <v-app-bar elevation="2" class="px-5">
        <v-toolbar-title>
            <v-img
                width="150px"
                src="https://framerusercontent.com/images/f0btmN2GtVDhwuoOUM5xAjorM.png"
                :style="
                    $vuetify.theme.global.current.dark
                        ? 'filter: brightness(0) invert(1)'
                        : ''
                "
            />
        </v-toolbar-title>

        <v-spacer></v-spacer>
        <div>
            <v-tooltip v-if="!$vuetify.theme.global.current.dark" bottom>
                <template v-slot:activator="{ on }">
                    <v-btn
                        v-on="on"
                        small
                        fab
                        @click="setTheme"
                        variant="outlined"
                    >
                        <v-icon class="mr-1">mdi-moon-waxing-crescent</v-icon>
                    </v-btn>
                </template>
                <span>Dark Mode On</span>
            </v-tooltip>

            <v-tooltip v-else bottom>
                <template v-slot:activator="{ on }">
                    <v-btn
                        v-on="on"
                        small
                        fab
                        @click="setTheme"
                        variant="outlined"
                    >
                        <v-icon color="yellow">mdi-white-balance-sunny</v-icon>
                    </v-btn>
                </template>
                <span>Dark Mode Off</span>
            </v-tooltip>
        </div>
    </v-app-bar>
</template>

<script>
export default {
    name: 'AppBarComponent',
    data() {
        return {}
    },
    mounted() {
        const theme = localStorage.getItem('upload-app-theme')

        if (theme) {
            this.$vuetify.theme.global.name = theme
        }
    },
    methods: {
        setTheme() {
            const newTheme = this.$vuetify.theme.global.current.dark
                ? 'light'
                : 'dark'

            localStorage.setItem('upload-app-theme', newTheme)
            this.$vuetify.theme.global.name = newTheme
        }
    }
}
</script>
