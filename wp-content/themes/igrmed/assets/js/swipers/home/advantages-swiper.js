import Swiper from 'swiper';
import { Pagination } from 'swiper/modules';

const wrapper = document.querySelector('.advantages__list--swiper');

if (wrapper) {
    new Swiper(wrapper, {
        modules: [Pagination],
        slidesPerView: 1.4,
        spaceBetween: 12,
        pagination: {
            el: '.advantages__list-pagination',
            clickable: true,
        },
    });
}
