export default class BlogShare {
    constructor() {
        this.copyButtons = document.querySelectorAll('.js-copy-link');
        if (!this.copyButtons.length) return;
        
        this.init();
    }

    init() {
        this.copyButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const url = button.getAttribute('data-url');
                this.copyToClipboard(url, button);
            });
        });
    }

    copyToClipboard(text, button) {
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(text).then(() => {
                this.showFeedback(button);
            });
        } else {
            // Fallback
            const textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.position = "fixed";
            textArea.style.left = "-9999px";
            textArea.style.top = "0";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                this.showFeedback(button);
            } catch (err) {
                console.error('Fallback: Oops, unable to copy', err);
            }
            document.body.removeChild(textArea);
        }
    }

    showFeedback(button) {
        button.classList.add('copied');
        setTimeout(() => {
            button.classList.remove('copied');
        }, 2000);
    }
}
