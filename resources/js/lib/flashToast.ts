import { router, usePage } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import type { FlashToast } from '@/types/ui';

export function initializeFlashToast(): void {
    // Listen for both navigate and success events
    router.on('success', () => {
        const page = usePage();
        const flash = page.props.flash as any;

        if (!flash?.toast) {
            return;
        }

        const data = flash.toast as FlashToast;
        toast[data.type](data.message);
    });
}
