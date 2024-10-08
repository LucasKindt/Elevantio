import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        var toastElList = document.querySelector('.toast');
        var carouselElement = document.querySelector('#carousel')

        if(toastElList !== null) {
            if(Array.isArray(toastElList) === false) {
                toastElList = [toastElList];
            }

            toastElList.forEach(function (toastEl) {
                console.log(true)
                const toast = bootstrap.Toast.getOrCreateInstance(toastEl);
                toast.show();
            });
        }

        if(carouselElement !== null) {
            const carousel = new bootstrap.Carousel(carouselElement, {
                interval: 10000,
                touch: false
            })
        }
    };

    startCarousel() {

    }
}
