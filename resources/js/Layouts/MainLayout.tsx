import { PropsWithChildren, useEffect } from 'react';
import { Img } from 'react-image';
import { Link } from '@inertiajs/react';
import { Search } from 'lucide-react';
import ApplicationLogo from '@/Components/ApplicationLogo';
import { Button } from '@/Components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import { Input } from '@/Components/ui/input';
import { usePage } from '@inertiajs/react';
import { PageProps } from '@/types';
import { Toaster } from '@/Components/ui/sonner';
import { toast } from "sonner"

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
                    <DropdownMenu>
                        <DropdownMenuTrigger asChild>
                            <Button
                                variant="outline"
                                size="icon"
                                className="overflow-hidden rounded-full"
                            >
                                <Img
                                    src="/images/placeholder-user.webp"
                                    width={36}
                                    height={36}
                                    alt="Avatar"
                                    className="overflow-hidden rounded-full"
                                />
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end">
                            <DropdownMenuLabel>My Account</DropdownMenuLabel>
                            <DropdownMenuSeparator />
                            <DropdownMenuItem>Settings</DropdownMenuItem>
                            <DropdownMenuItem>Support</DropdownMenuItem>
                            <DropdownMenuSeparator />
                            <DropdownMenuItem>
                                <Link method="post" href={route('logout')} as="button">
                                    Logout
                                </Link>
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
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

            <Toaster expand={true} richColors position="bottom-right" />
        </div>
    );
}
