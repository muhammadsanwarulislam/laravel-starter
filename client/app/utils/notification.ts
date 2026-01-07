export const notification = {
  success(message: string) {
    this.show(message, 'success')
  },

  error(message: string) {
    this.show(message, 'error')
  },

  info(message: string) {
    this.show(message, 'info')
  },

  warning(message: string) {
    this.show(message, 'warning')
  },

  show(message: string, type: 'success' | 'error' | 'info' | 'warning' = 'info') {
    if (!process.client) return

    // Remove existing toast
    const existingToast = document.getElementById('global-toast')
    if (existingToast) {
      existingToast.remove()
    }

    // Create toast element
    const toast = document.createElement('div')
    toast.id = 'global-toast'

    const colorClasses = {
      success: 'bg-green-500 text-white',
      error: 'bg-red-500 text-white',
      info: 'bg-blue-500 text-white',
      warning: 'bg-yellow-500 text-white'
    }

    toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 transform translate-y-0 ${colorClasses[type]}`
    toast.textContent = message

    document.body.appendChild(toast)

    // Auto remove after 3 seconds
    setTimeout(() => {
      toast.style.transform = 'translateY(-100px)'
      toast.style.opacity = '0'
      setTimeout(() => toast.remove(), 300)
    }, 3000)
  }
}