import '../styles/generic_form.scss'
import { toastError, ToastType } from './toast'

let index: number = 0
const choiceFieldType: number = 4
const _fieldsPrototype = (<HTMLInputElement>(
  document.querySelector('[name="fields_prototype"]')
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
  return _fieldsPrototype
    .replace(/\<div id="form_fields___name__"\>|(?:<\/div>)$/gm, '')
    .replace(/__name__/g, (++index).toString())
}

/**
 * Checks if there are any fields that are not filled.
 *
 * @return {boolean} True if there are some fields that are not filled, false otherwise.
 */
const checkIsSomeFieldsAreNotFilled = (): boolean =>
  Array.from(
    document.querySelectorAll<HTMLInputElement | HTMLSelectElement>(
      'input,select'
    )
  ).some((e) => !e.value)

/**
 * Handles the change event of the field type selection.
 *
 * @param {Event} el - The event object.
 * @return {void} This function does not return anything.
 */
const handleSelectFieldType = (el: Event): void => {
  const elDom = el.target as HTMLInputElement
  const optionsContainer = elDom?.parentElement?.nextElementSibling
  const fieldset = optionsContainer?.querySelector('fieldset')
  const typeField: number | null = +elDom?.value || null
  if (typeField === choiceFieldType) {
    fieldset?.classList.toggle('d-none')
  } else {
    fieldset?.classList.add('d-none')
  }
}
/**
 * Adds a new field row to the DOM.
 *
 * @return {void} This function does not return anything.
 */
const addField = (): void => {
  if (!checkIsSomeFieldsAreNotFilled()) {
    const divSection = document.createElement('div')
    divSection.classList.add('field-row')
    divSection.innerHTML = getSanitizedTemplate()

    divSection?.querySelector('fieldset')?.appendChild(getDeleteButtonCTA())
    const container = document.querySelector('#fields_container')
    container?.insertBefore(divSection, document.querySelector('#add_row'))
    const hr = document.createElement('hr')
    divSection.insertAdjacentElement('beforeend', hr)
    //handle set option by field type
    divSection
      ?.querySelector('.field-type-selection')
      ?.addEventListener('change', handleSelectFieldType)
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
  document
    .querySelector('.field-type-selection')
    ?.addEventListener('change', handleSelectFieldType)
}
