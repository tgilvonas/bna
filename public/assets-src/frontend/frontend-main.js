import { createApp, ref, watch, onMounted, onBeforeUnmount } from 'vue';

const mobileMenuOpen = ref(false);

const app = createApp({
    setup() {
        let opener;
        let backdrop;
        let menu;
        let closeButton;
        let closeTimer;

        const applyState = (isOpen) => {
            if (!opener || !backdrop || !menu) return;

            window.clearTimeout(closeTimer);
            opener.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            document.body.classList.toggle('mobile-menu-open', isOpen);

            if (isOpen) {
                menu.hidden = false;
                backdrop.hidden = false;
                window.requestAnimationFrame(() => {
                    menu.classList.add('is-open');
                    backdrop.classList.add('is-open');
                    closeButton?.focus();
                });
            } else {
                menu.classList.remove('is-open');
                backdrop.classList.remove('is-open');
                closeTimer = window.setTimeout(() => {
                    menu.hidden = true;
                    backdrop.hidden = true;
                }, 350);
            }
        };

        watch(mobileMenuOpen, applyState);

        const toggleMenu = () => {
            mobileMenuOpen.value = !mobileMenuOpen.value;
        };

        const closeMenu = () => {
            mobileMenuOpen.value = false;
        };

        const closeOnEscape = (event) => {
            if (event.key === 'Escape' && mobileMenuOpen.value) {
                closeMenu();
            }
        };

        onMounted(() => {
            opener = document.querySelector('#mobile-menu-opener');
            backdrop = document.querySelector('.mobile-menu-backdrop');
            menu = document.querySelector('.vue-mobile-menu');
            closeButton = document.querySelector('.close-mobile-menu');

            if (!opener || !backdrop || !menu) return;

            opener.addEventListener('click', toggleMenu);
            backdrop.addEventListener('click', closeMenu);
            closeButton?.addEventListener('click', closeMenu);
            document.addEventListener('keydown', closeOnEscape);

            mobileMenuOpen.value = false;
            applyState(false);
        });

        onBeforeUnmount(() => {
            window.clearTimeout(closeTimer);
            document.removeEventListener('keydown', closeOnEscape);
        });

        return () => null;
    },
    render() {
        return null;
    }
});

app.mount(document.createElement('div'));
