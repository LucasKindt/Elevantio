import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['childrenContainer', 'prototype'];

    add(event) {
        event.preventDefault();

        const index = this.childrenContainerTarget.children.length -1;  // Get current number of children
        const prototype = this.prototypeTarget.innerHTML.replace(/__name__/g, index);

        // Add the prototype inside the children container
        this.childrenContainerTarget.insertAdjacentHTML('beforeend', prototype);
    }

    remove(event) {
        event.preventDefault();
        event.target.closest('tr').remove();
    }

    submit(event) {
        event.preventDefault();
        this.prototypeTarget.innerHTML = '';
        document.getElementById("childrenForm").submit();
    }
}
