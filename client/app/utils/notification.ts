type ToastType = 'success' | 'error' | 'info' | 'warning';

interface ToastOptions {
  title?: string;
  message: string;
}

const defaultTitles: Record<ToastType, string> = {
  success: 'Success!',
  error: 'Error!',
  info: 'Info',
  warning: 'Warning'
};


const icons: Record<ToastType, string> = {
  success: `<svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
  </svg>`,
  error: `<svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
  </svg>`,
  info: `<svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
  </svg>`,
  warning: `<svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
  </svg>`
};


const iconCircleBg: Record<ToastType, string> = {
  success: 'bg-green-100 dark:bg-green-900/30',
  error: 'bg-red-100 dark:bg-red-900/30',
  info: 'bg-blue-100 dark:bg-blue-900/30',
  warning: 'bg-yellow-100 dark:bg-yellow-900/30'
};

export const notification = {
  success(messageOrOptions: string | ToastOptions) {
    this.show(messageOrOptions, 'success');
  },

  error(messageOrOptions: string | ToastOptions) {
    this.show(messageOrOptions, 'error');
  },

  info(messageOrOptions: string | ToastOptions) {
    this.show(messageOrOptions, 'info');
  },

  warning(messageOrOptions: string | ToastOptions) {
    this.show(messageOrOptions, 'warning');
  },

  show(
    input: string | ToastOptions,
    type: ToastType = 'info'
  ) {
    if (!process.client) return;

    // Resolve title & message
    let title: string;
    let message: string;
    if (typeof input === 'string') {
      title = defaultTitles[type];
      message = input;
    } else {
      title = input.title ?? defaultTitles[type];
      message = input.message;
    }

    const existingToast = document.getElementById('global-toast');
    if (existingToast) existingToast.remove();

    if ((window as any).__toastTimeout) clearTimeout((window as any).__toastTimeout);

    const toast = document.createElement('div');
    toast.id = 'global-toast';
    toast.className = `fixed bottom-6 right-6 z-50 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-4 max-w-sm transition-all duration-300 ease-out`;
    toast.style.transform = 'translateX(100%)';
    toast.style.opacity = '0';

    toast.innerHTML = `
      <div class="flex items-start gap-3">
        <div class="shrink-0">
          <div class="w-8 h-8 ${iconCircleBg[type]} rounded-full flex items-center justify-center">
            ${icons[type]}
          </div>
        </div>
        <div class="flex-1">
          <p class="font-medium text-gray-900 dark:text-white">${escapeHtml(title)}</p>
          <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">${escapeHtml(message)}</p>
        </div>
        <button class="close-toast text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
          <span>✕</span>
        </button>
      </div>
    `;

    document.body.appendChild(toast);

    toast.getBoundingClientRect();
    toast.style.transform = 'translateX(0)';
    toast.style.opacity = '1';

    const closeToast = () => {
      toast.style.transform = 'translateX(100%)';
      toast.style.opacity = '0';
      setTimeout(() => toast.remove(), 300);
    };

    const closeBtn = toast.querySelector('.close-toast') as HTMLButtonElement;
    if (closeBtn) closeBtn.onclick = (e) => {
      e.preventDefault();
      closeToast();
      if ((window as any).__toastTimeout) clearTimeout((window as any).__toastTimeout);
    };

    (window as any).__toastTimeout = setTimeout(closeToast, 3000);
  }
};

function escapeHtml(str: string): string {
  return str
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#39;');
}