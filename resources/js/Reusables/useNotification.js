import { ref } from 'vue'

const showNotification = ref(false)
const notificationContent = ref({
    type: 'success',
    title: 'Materom',
    description: 'Materom notification',
    timeoutInSeconds: 2
})

export default function useNotification() {
    return {
        showNotification,
        notificationContent
    }
}
