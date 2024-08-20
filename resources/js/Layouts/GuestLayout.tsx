import { PropsWithChildren } from 'react';
import { Toaster } from "@/Components/ui/toaster"

export default function GuestLayout({ children }: PropsWithChildren) {
    return (
        <div className="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div className="w-full sm:max-w-md mt-6 px-6 py-4 overflow-hidden sm:rounded-lg">
                {children}
            </div>
            <Toaster />
        </div>
    );
}
