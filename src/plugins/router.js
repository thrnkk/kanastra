import router from '../router/index.js'

// eslint-disable-next-line no-unused-vars
router.afterEach((to, from) => {
    nextTick(() => {
        document.title = 'Quero mais Rodeios :: ' + to.meta.title ?? 'Quero mais Rodeios';
    });
});

export default router