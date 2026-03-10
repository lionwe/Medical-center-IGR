class WhyChooseUs {
    constructor() {
        this.containers = document.querySelectorAll('.js-video-container');
        this.init();
    }

    init() {
        if (!this.containers.length) return;

        this.containers.forEach(container => {
            const video = container.querySelector('.js-video');
            const playBtn = container.querySelector('.js-video-play');
            const overlay = container.querySelector('.js-video-overlay');

            if (!video || !playBtn || !overlay) return;

            playBtn.addEventListener('click', () => {
                if (video.paused) {
                    video.play();
                    overlay.classList.add('is-hidden');
                } else {
                    video.pause();
                    overlay.classList.remove('is-hidden');
                }
            });

            video.addEventListener('click', () => {
                if (!video.paused) {
                    video.pause();
                    overlay.classList.remove('is-hidden');
                }
            });

            // Show overlay again when video ends
            video.addEventListener('ended', () => {
                overlay.classList.remove('is-hidden');
            });
        });
    }
}

export default WhyChooseUs;
