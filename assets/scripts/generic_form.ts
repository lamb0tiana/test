import '../styles/generic_form.scss'
import { toastError, ToastType } from './toast'

let index: number = 0
const choiceFieldType: number = 4
const _fieldsPrototype = (<HTMLInputElement>(
  document.querySelector('[name="fields_prototype"]')
)).value
const _optionPrototype = (<HTMLInputElement>(
  document.querySelector('[name="attribute_option_prototype"]')
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

const checkIsSomeOptionsAreNotFilled = (): boolean =>
  Array.from(
    document.querySelectorAll<HTMLInputElement>('.row-option-item>input')
  ).some((e) => !e.value)

/**
 * Returns an Icon element with the given class list.
 *
 * @param classLIst - The list of classes to be added to the element.
 * @returns The HTML element with the specified classes.
 */
const createIcon = (classLIst: string[]): HTMLElement => {
  const icon = document.createElement('i')
  icon.classList.add(...classLIst)
  return icon
}

/**
 * Creates a button element with the given class list.
 *
 * @param classList The list of classes to be added to the button element.
 * @return The HTML button element with the specified classes.
 */
const createButton = (classList: string[]): HTMLElement => {
  const btn = document.createElement('button')
  btn.setAttribute('type', 'button')
  btn.classList.add(...classList)
  return btn
}

/**
 * Defines the structure of the option row for choice field type.
 *
 * @return {HTMLDivElement} The div element containing the option row.
 */
const getOptionRow = (): HTMLDivElement => {
  const container = document.createElement('div')
  container.classList.add('d-flex', 'mb-3', 'row-option-item')
  container.innerHTML = _optionPrototype
  const deleteBtn = createButton(['btn', 'btn-danger', 'btn-sm', 'mx-2'])
  deleteBtn.onclick = () => container.remove()
  deleteBtn.appendChild(createIcon(['bi', 'bi-trash3']))
  container.insertAdjacentElement('beforeend', deleteBtn)

  return container
}

/**
 * Creates a button element for adding an option.
 *
 * @return {HTMLElement} The button element for adding an option.
 */
const createCTAOptionButton = (): HTMLElement => {
  const btn = createButton(['btn', 'btn-sm', 'btn-primary', 'cta-btn-option'])
  btn.appendChild(createIcon(['bi', 'bi-plus']))
  btn.onclick = () => {
    if (!checkIsSomeOptionsAreNotFilled()) {
      btn.parentElement?.parentElement?.appendChild(getOptionRow())
    } else {
      toastError({
        message: 'Renseigner les options',
        type: ToastType.ERROR,
        title: 'Erreur Options',
      })
    }
  }
  return btn
}

/**
 * Handles the change event of the field type selection.
 *
 * @param {Event} el - The event object.
 * @return {void} This function does not return anything.
 */
const handleFieldTypeSelection = (el: Event): void => {
  const elDom = el.target as HTMLInputElement
  const optionsContainer = elDom?.parentElement?.nextElementSibling
  const fieldset = optionsContainer?.querySelector('fieldset')
  if (!fieldset) return
  const typeField: number | null = +elDom?.value || null
  if (typeField === choiceFieldType) {
    //show the option prototype
    fieldset.classList.toggle('d-none')
    const legend = fieldset.querySelector('legend')
    legend?.insertAdjacentElement('beforeend', createCTAOptionButton())
    fieldset.appendChild(getOptionRow())
  } else {
    document
      .querySelectorAll('.row-option-item,.cta-btn-option')
      .forEach((e) => e.remove())
    fieldset.classList.add('d-none')
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
      ?.addEventListener('change', handleFieldTypeSelection)
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
    ?.addEventListener('change', handleFieldTypeSelection)
}
