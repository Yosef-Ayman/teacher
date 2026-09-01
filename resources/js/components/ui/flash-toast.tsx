import { useEffect, useRef } from 'react';
import { usePage } from '@inertiajs/react';
import { toast } from 'sonner';

interface FlashToastData {
    type: 'success' | 'error' | 'warning' | 'info';
    message: string;
}

export function FlashToast() {
    const { flash } = usePage<{ flash: { toast?: FlashToastData } }>().props;

    const lastShown = useRef<string | null>(null);

    useEffect(() => {
        if (!flash?.toast) return;

        const { type, message } = flash.toast;
        const id = `flash-${type}-${message}`;

        if (lastShown.current === id) return;
        lastShown.current = id;

        toast[type](message, { id });
    }, [flash?.toast]);

    return null;
}
