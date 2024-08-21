import { PropsWithChildren, useEffect } from 'react'
import { Img } from 'react-image'
import { Link } from '@inertiajs/react'
import { Search } from 'lucide-react'

import { Badge } from '@/Components/ui/badge'
import { Button } from '@/Components/ui/button'
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu'
import { Input } from '@/Components/ui/input'
import { usePage } from '@inertiajs/react'
import { PageProps } from '@/types'
import { useToast } from '@/Components/ui/use-toast'
import { Toaster } from '@/Components/ui/toaster'

export default function MainLayout({ children }: PropsWithChildren) {
    const { auth } = usePage<PageProps>().props
    const { toast } = useToast()
    const { flash } = usePage<{ flash: { message?: string } }>().props;

    useEffect(() => {
        if (flash?.message) {
            toast({
                description: flash?.message,
            })
        }
    }, [flash?.message, toast]);

    return (
        <div className="flex min-h-screen w-full flex-col bg-muted/40">
            <div className="flex flex-col sm:gap-4 sm:py-4 sm:pl-14">
                <header className="sticky top-0 z-40 flex h-14 items-center gap-4 border-b bg-background px-4 sm:static sm:h-auto sm:border-0 sm:bg-transparent sm:px-6">
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
                <main className="grid flex-1 items-start gap-4 p-4 sm:px-6 sm:py-0 md:gap-8">
                    {children}
                    <Toaster />
                </main>
            </div>
        </div>
    )
}
