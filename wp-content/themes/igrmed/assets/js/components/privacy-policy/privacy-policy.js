/**
 * Privacy Policy Page JavaScript - Read More logic
 */
class PrivacyPolicy {
    constructor() {
        this.container = document.querySelector('.js-privacy-content');
        if (!this.container) return;

        this.init();
    }

    init() {
        const textWrapper = this.container.querySelector('.privacy-content__text');
        const moreWrapper = this.container.querySelector('.privacy-content__more-wrapper');
        const moreContent = this.container.querySelector('.privacy-content__more-content');
        const readMoreBtn = this.container.querySelector('.js-read-more-btn');

        if (!textWrapper || !moreWrapper || !moreContent || !readMoreBtn) return;

        // Collect all top-level children
        const children = Array.from(textWrapper.children);
        let h2Count = 0;
        let splitIndex = -1;

        // Find the split point: after 5th H2 and its following content (usually P)
        for (let i = 0; i < children.length; i++) {
            const child = children[i];
            if (child.tagName === 'H2') {
                h2Count++;
            }

            // If we've reached 5 H2s, we want to split AFTER the content following this H2
            if (h2Count === 5) {
                // Find next H2 to see where this section ends, or if it's the last element
                let nextH2Index = children.findIndex((el, idx) => idx > i && el.tagName === 'H2');
                
                if (nextH2Index !== -1) {
                    splitIndex = nextH2Index;
                } else {
                    // All remaining content belongs to the 5th section, but maybe we don't need a split if there's nothing after.
                    splitIndex = -1; 
                }
                break;
            }
        }

        // If we have content to hide
        if (splitIndex !== -1 && splitIndex < children.length) {
            moreWrapper.classList.add('is-visible');

            // Move elements to the moreContent container
            for (let i = splitIndex; i < children.length; i++) {
                moreContent.appendChild(children[i]);
            }

            // Toggle logic
            const btnSpan = readMoreBtn.querySelector('span');
            const originalText = btnSpan.innerText;
            const collapseText = readMoreBtn.getAttribute('data-collapse-text') || 'Згорнути';

            readMoreBtn.addEventListener('click', () => {
                const isOpen = moreWrapper.classList.toggle('is-open');
                btnSpan.innerText = isOpen ? collapseText : originalText;
                readMoreBtn.setAttribute('aria-expanded', isOpen);
            });
        }
    }

    static init() {
        new PrivacyPolicy();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    PrivacyPolicy.init();
});

export default PrivacyPolicy;
