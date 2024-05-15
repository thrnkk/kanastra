import { toast } from 'vue3-toastify';

export const toastMixin = {
    methods: {
        toast(type, message, closeIn = 2000) {
            toast[type](
                message, 
                {
                    theme: 'dark', 
                    clearOnUrlChange: false,
                    dangerouslyHTMLString: true, 
                    autoClose: closeIn,
                    position: 'bottom-right',
                    pauseOnFocusLoss: false,
                    newestOnTop: true
                }
            )

            return
        }
    }
}