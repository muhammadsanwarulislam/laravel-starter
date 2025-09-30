import { reactive, readonly } from 'vue'

const state = reactive({
  visible: false,
  type: 'success', 
  title: '',
  message: '',
  showLink: false,
  linkText: '',
  linkHref: '',
})

let timeout

export function useAlert() {
  function showAlert({
    type = 'success',
    title = '',
    message = '',
    showLink = false,
    linkText = '',
    linkHref = '',
    duration = 3000,
  }) {
    Object.assign(state, {
      visible: true,
      type,
      title,
      message,
      showLink,
      linkText,
      linkHref,
    })

    clearTimeout(timeout)
    timeout = setTimeout(() => {
      state.visible = false
    }, duration)
  }

  return {
    alertState: readonly(state),
    showAlert,
  }
}
