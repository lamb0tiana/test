import '../styles/generic_form.scss'
import { toastError, ToastType } from './toast'

const _prototype = (<HTMLInputElement>(
  document.querySelector('[name="prototype"]')
)).value

/**
 * Deletes a field row from the DOM.
 *
 * @param {HTMLButtonElement} el - The button element that triggers the deletion.
 * @return {void} This function does not return anything.
 */
const deleteRow = (el: HTMLButtonElement): void => {
  el.closest('div')?.remove()
}

/**
 * Returns a button element that, when clicked, deletes a field row from the DOM.
 *
 * @return {HTMLButtonElement} The button element.
 */
const getDeleteButtonCTA = (): HTMLButtonElement => {
  const btn = document.createElement('button')
  const classes = ['btn', 'btn-danger', 'btn-sm', 'mx-2']
  btn.classList.add(...classes)
  btn.innerText = 'supprimer'
  btn.addEventListener('click', (evt: MouseEvent) =>
    deleteRow(evt.target as HTMLButtonElement)
  )
  return btn
}

/**
 * Returns a sanitized version of the prototype template, with __name__ replaced
 * by the current number of fieldsets.
 *
 * @return {string} The sanitized template.
 */
const getSanitizedTemplate = (): string => {
  const count = document.querySelectorAll('#fields_container>fieldset').length
  return _prototype
    .replace(/\<div id="form_fields___name__"\>|(?:<\/div>)$/gm, '')
    .replace(/__name__/g, count.toString())
}
const checkIsSomeFieldsAreNotFilled = () =>
  Array.from(
    document.querySelectorAll<HTMLInputElement | HTMLSelectElement>(
      'input,select'
    )
  ).filter((e) => !e.value).length !== 0

/**
 * Adds a new field row to the DOM.
 *
 * @return {void} This function does not return anything.
 */
const addField = (): void => {
  if (!checkIsSomeFieldsAreNotFilled()) {
    const divSection = document.createElement('div')
    divSection.classList.add('field_row')
    divSection.innerHTML = getSanitizedTemplate()

    divSection?.querySelector('fieldset')?.appendChild(getDeleteButtonCTA())
    const container = document.querySelector('#fields_container')
    container?.insertBefore(divSection, document.querySelector('#add_row'))
    const hr = document.createElement('hr')
    divSection.insertAdjacentElement('afterend', hr)
  } else {
    toastError({
      message: 'Merci de bien remplir le formulaire',
      type: ToastType.ERROR,
      title: 'Erreur !',
    })
  }
}

window.onload = () => {
  const addButton = document.querySelector('#add_row')
  addButton?.addEventListener('click', addField)
}
