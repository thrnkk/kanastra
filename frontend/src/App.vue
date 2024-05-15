<template>
    <v-layout class="rounded rounded-md" id="app">
        <navigation-drawer></navigation-drawer>
        <app-bar></app-bar>

        <div class="d-flex flex-column w-100">
            <v-main class="fill-height">
                <div class="pa-5 fill-height">
                    <router-view class="fill-height" style="overflow-y: auto" />
                </div>
            </v-main>
        </div>
    </v-layout>
</template>

<script>
import NavigationDrawer from './components/NavigationDrawer.vue'
import AppBar from './components/AppBar.vue'

export default {
    name: 'AppComponent',
    components: { NavigationDrawer, AppBar },
    data() {
        return {}
    },
    methods: {
        setTheme() {
            this.$vuetify.theme.global.name = this.$vuetify.theme.global.current
                .dark
                ? 'light'
                : 'dark'
        }
    }
}
</script>

<style scoped>
#app {
    font-family: 'Roboto', sans-serif !important;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    margin: 0;
    height: 100vh;
    width: 100%;
    font-size: 14px;
}
</style>