import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        var toastElList = document.querySelector('.toast');

        if(toastElList === null) {
            return;
        }

        if(Array.isArray(toastElList) === false) {
            toastElList = [toastElList];
        }

        toastElList.forEach(function (toastEl) {
            console.log(true)
            const toast = bootstrap.Toast.getOrCreateInstance(toastEl);
            toast.show();
        });
    };
}
