const _prototype = (<HTMLInputElement>document.querySelector('[name="prototype"]')).value
/**
 * Deletes a row from the DOM.
 *
 * @param {HTMLButtonElement} el - The button element that triggers the deletion.
 * @return {void} This function does not return anything.
 */
function deleteRow(el: HTMLButtonElement){
    el.parentElement.parentNode.previousSibling.remove()
    el.parentElement.parentNode.remove()
}
window.onload = () => {
    document.querySelector('#fields_container button')?.addEventListener('click', function (e){
        const count = document.querySelectorAll('#fields_container>div').length
        const regex = /\<div id="form_fields___name__"\>|(?:<\/div>)$/gm;
        const template = _prototype.replace(regex,'').replaceAll('__name__',count.toString())
        document.querySelector('#fields_container button')?.insertAdjacentHTML('beforebegin',template);
        document.querySelector('#fields_container > div:last-of-type >div')?.insertAdjacentHTML('beforeend', '<button onclick="deleteRow(this)">supprimer</button>')
    })
}