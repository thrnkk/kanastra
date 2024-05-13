import { createRouter, createWebHistory } from 'vue-router'

const routes = [
    {
        path: '/upload',
        name: 'upload.view',
        component: () => import('../views/UploadView.vue'),
        meta: { title: 'Uploads' }
    },
    {
        path: '/list',
        name: 'upload.list.view',
        component: () => import('../views/ListView.vue'),
        meta: { title: 'Listagem de uploads' }
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
    linkActiveClass: "active"
})

export default router