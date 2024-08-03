import * as bootstrap from 'bootstrap'
export enum ToastType {
  SUCCESS = 'success',
  ERROR = 'danger',
}
type ToastOptions = {
  type: ToastType
  title: string
  message: string
}

export const toastError = ({ message, type, title }: ToastOptions) => {
  const toast = document.getElementById('toast_action')
  if (!toast) {
    return
  }
  toast.innerHTML = `
    <div class="toast-header text-bg-${type}">
        <strong class="me-auto">${title}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body text-bg-${type} text-center ">
        ${message}
    </div>
  `
  const toastAlert = bootstrap.Toast.getOrCreateInstance(toast)
  toastAlert.show()
}
