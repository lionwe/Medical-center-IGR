class Advantages {
    constructor(wrapper) {
        this.wrapper = wrapper;
        this.counters = this.wrapper.querySelectorAll('.js-count-up');

        if (!this.counters.length) {
            return;
        }

        this.initObserver();
    }

    initObserver() {
        const options = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.startCountUp(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, options);

        this.counters.forEach(counter => observer.observe(counter));
    }

    startCountUp(element) {
        let rawTarget = element.getAttribute('data-value') || '';
        // In case there are spaces or formats, remove them except digits
        let numericTargetStr = rawTarget.replace(/\D/g, '');
        const target = parseInt(numericTargetStr, 10);

        if (isNaN(target) || target === 0) {
            element.textContent = rawTarget;
            return;
        }

        const duration = 2000;
        let startTimestamp = null;
        const initialValue = 0;

        const animate = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const elapsed = timestamp - startTimestamp;
            const progress = Math.min(elapsed / duration, 1);

            // easeOutQuad
            const easeProgress = progress * (2 - progress);
            const currentVal = Math.floor(easeProgress * target);

            // Preserve non-numeric characters if needed, but for count-up usually it's just numbers.
            // If they had "100+", we format it back.
            let suffix = rawTarget.replace(/[0-9]/g, ''); // Extract non-numbers like "+", "%"
            element.textContent = currentVal + suffix;

            if (progress < 1) {
                window.requestAnimationFrame(animate);
            } else {
                element.textContent = target + suffix;
            }
        };

        window.requestAnimationFrame(animate);
    }
}

document.querySelectorAll('.advantages').forEach(wrapper => {
    new Advantages(wrapper);
});
