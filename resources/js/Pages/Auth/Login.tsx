import { FormEventHandler } from 'react'
import { Button } from '@/Components/ui/button'
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/Components/ui/card'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import InputError from '@/Components/InputError'
import { Checkbox } from "@/Components/ui/checkbox"
import { Head, useForm } from '@inertiajs/react'
import { Link } from '@inertiajs/react'
import GuestLayout from '@/Layouts/GuestLayout'

interface LoginProps {
    status?: string
    canResetPassword: boolean
}

export default function Login({ status, canResetPassword }: LoginProps) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    })

    const submit: FormEventHandler<HTMLFormElement> = (e) => {
        e.preventDefault()
        post(route('login'), {
            onFinish: () => reset('password'),
        })
    }

    return (
        <GuestLayout>
            <Head title="Log in" />

            {status && <div className="mb-4 font-medium text-sm text-green-600">{status}</div>}

            <Card className="mx-auto max-w-sm mt-10">
                <CardHeader>
                    <CardTitle className="text-2xl">Login</CardTitle>
                    <CardDescription>
                        Enter your credentials below to log in.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <form onSubmit={submit} className="grid gap-4">
                        <div className="grid gap-2">
                            <Label htmlFor="email">Email</Label>
                            <Input
                                id="email"
                                type="email"
                                placeholder="m@example.com"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                required
                            />
                            <InputError message={errors.email} />
                        </div>

                        <div className="grid gap-2">
                            <div className="flex items-center">
                                <Label htmlFor="password">Password</Label>
                                {canResetPassword && (
                                    <Link
                                        href={route('password.request')}
                                        className="ml-auto text-sm underline"
                                    >
                                        Forgot your password?
                                    </Link>
                                )}
                            </div>
                            <Input
                                id="password"
                                type="password"
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                                required
                            />
                            <InputError message={errors.password} />
                        </div>

                        <div className="flex items-center space-x-2">
                            <Checkbox id="remember" />
                            <label
                                htmlFor="remember"
                                id="remember"
                                className="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                            >
                                Remember me
                            </label>
                        </div>

                        <Button type="submit" className="w-full" disabled={processing}>
                            Login
                        </Button>
                    </form>
                </CardContent>
            </Card>
        </GuestLayout>
    )
}
