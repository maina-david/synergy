import { PropsWithChildren, useEffect } from 'react';
import { Toaster } from '@/Components/ui/sonner';
import { toast } from "sonner"
import { usePage } from '@inertiajs/react';

export default function GuestLayout({ children }: PropsWithChildren) {
    const { flash } = usePage<{ flash: { status?: string, error?: string, success?: string } }>().props;

    useEffect(() => {
        if (flash?.status) {
            toast(flash?.status)
        }
        if (flash?.error) {
            toast.error('Uh oh! Something went wrong.', {
                description: flash?.error,
            })
        }
        if (flash?.success) {
            toast.success(flash?.success)
        }
    }, [flash?.error, flash?.success, toast]);

    return (
        <div className="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div className="w-full sm:max-w-md mt-6 px-6 py-4 overflow-hidden sm:rounded-lg">
                {children}
            </div>
            <Toaster expand={true} position="bottom-right" />
        </div>
    );
}
