import { Head, Link, useForm } from '@inertiajs/react';
import { Button } from "@/Components/ui/button"
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card"
import { Input } from "@/Components/ui/input"
import { Label } from "@/Components/ui/label"
import { FormEventHandler, ReactNode } from 'react';
import { useToast } from "@/Components/ui/use-toast";
import { FaSpinner } from "react-icons/fa";
import GuestLayout from '@/Layouts/GuestLayout';

const Login = ({ status, canResetPassword }: { status?: string, canResetPassword: boolean }) => {
    const { toast } = useToast();
    const { data, setData, setError, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('login'), {
            onFinish: () => {
                reset('email', 'password');
            },
            onError: () => {
                if (errors.email) {
                    toast({
                        variant: 'destructive',
                        title: 'Login Failed',
                        description: errors.email,
                    });
                }

                if (errors.password) {
                    toast({
                        variant: 'destructive',
                        title: 'Login Failed',
                        description: errors.password,
                    });
                }

                if (!errors.email && !errors.password) {
                    toast({
                        variant: 'destructive',
                        title: 'Login Failed',
                        description: 'There was a problem with your login credentials.',
                    });
                }
            },
        });
    };

    return (
        <>
            <Head title="Login" />
            <Card className="mx-auto max-w-sm">
                <CardHeader>
                    <CardTitle className="text-2xl">Login</CardTitle>
                    <CardDescription>
                        Enter your credentials below to access your account
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form onSubmit={submit}>
                        <div className="grid gap-4">
                            <div className="grid gap-2">
                                <Label htmlFor="email">Email</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value={data.email}
                                    placeholder="m@example.com"
                                    required
                                    autoComplete="username"
                                    onChange={(e) => setData('email', e.target.value)}
                                    autoFocus
                                />
                            </div>
                            <div className="grid gap-2">
                                <div className="flex items-center">
                                    <Label htmlFor="password">Password</Label>
                                    {canResetPassword && (
                                        <Link href="/forgot-password" className="ml-auto inline-block text-sm underline">
                                            Forgot your password?
                                        </Link>
                                    )}
                                </div>
                                <Input
                                    id="password"
                                    type="password"
                                    required
                                    value={data.password}
                                    autoComplete="current-password"
                                    onChange={(e) => setData('password', e.target.value)}
                                />
                            </div>
                            <Button type="submit" className="w-full" disabled={processing}>
                                {processing && (
                                    <FaSpinner className='animate-spin mr-2' />
                                )}
                                Login
                            </Button>
                        </div>
                        <div className="mt-4 text-center text-sm">
                            Don&apos;t have an account?{" "}
                            <Link href="#" className="underline">
                                Contact us.
                            </Link>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </>
    )
}

Login.layout = (page: ReactNode) => <GuestLayout children={page} />

export default Login
