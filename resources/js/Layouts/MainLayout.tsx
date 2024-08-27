import { PropsWithChildren, useEffect } from 'react';
import { Link } from '@inertiajs/react';
import { Search } from 'lucide-react';
import ApplicationLogo from '@/Components/ApplicationLogo';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { usePage } from '@inertiajs/react';
import { PageProps } from '@/types';
import { Toaster } from '@/Components/ui/sonner';
import { toast } from "sonner"
import CartDropdown from '@/Components/Cart';
import UserDropdown from '@/Components/UserDropdown';
import {
    QueryClient,
    QueryClientProvider,
} from '@tanstack/react-query'

const queryClient = new QueryClient();

export default function MainLayout({ children }: PropsWithChildren) {
    const { auth } = usePage<PageProps>().props;
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
            <QueryClientProvider client={queryClient}>
                <div className="flex min-h-screen w-full flex-col bg-muted/40">
                    <header className="sticky top-0 z-50 flex h-16 items-center gap-4 border-b bg-white shadow-md px-4 sm:px-6">
                        <div className="shrink-0 flex items-center">
                            <Link href="/">
                                <ApplicationLogo className="block h-9 w-auto fill-primary text-primary" />
                            </Link>
                        </div>
                        <div className="relative ml-auto flex-1 md:grow-0">
                            <Search className="absolute left-2.5 top-2.5 h-4 w-4 text-muted-foreground" />
                            <Input
                                type="search"
                                placeholder="Search..."
                                className="w-full rounded-lg bg-background pl-8 md:w-[200px] lg:w-[336px]"
                            />
                        </div>
                        {auth.user ? (
                            <>
                                <CartDropdown />
                                <UserDropdown />
                            </>
                        ) : (
                            <div className="flex items-center gap-4">
                                <Link href="/login">
                                    <Button variant="outline">Login</Button>
                                </Link>
                            </div>
                        )}
                    </header>

                    <main className="flex-1">
                        {children}
                    </main>

                    <Toaster expand={true} position="bottom-right" />
                </div>
            </QueryClientProvider>
    );
}
