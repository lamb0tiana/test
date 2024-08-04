import { toastError, ToastType } from './toast'

window.onload = () => {
  toastError({
    message: 'Merci !',
    type: ToastType.SUCCESS,
    title: 'Success !',
  })
}
