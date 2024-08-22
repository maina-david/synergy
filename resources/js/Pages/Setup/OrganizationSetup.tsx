import { Head, useForm } from "@inertiajs/react";
import { ChevronLeft, Upload } from "lucide-react";
import { Button } from "@/Components/ui/button";
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/Components/ui/card";
import { Input } from "@/Components/ui/input";
import { Label } from "@/Components/ui/label";
import { Textarea } from "@/Components/ui/textarea";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/Components/ui/select";
import InputError from "@/Components/InputError";
import { FormEventHandler } from "react";

interface FormData {
    name: string;
    description: string;
    email: string;
    phone: string;
    website: string;
    category: string;
    logo: File | undefined;
}

export default function OrganizationSetup() {
    const { data, setData, post, processing, errors } = useForm<FormData>({
        name: '',
        description: '',
        email: '',
        phone: '',
        website: '',
        category: '',
        logo: undefined
    });

    const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0] || undefined;
        setData('logo', file);
    };

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('organization.store'));
    };

    return (
        <>
            <Head title="Organization Setup" />
            <form onSubmit={submit}>
                <div className="mx-auto mt-4 grid flex-1 auto-rows-max gap-4">
                    <div className="flex items-center gap-4">
                        <h1 className="flex-1 shrink-0 whitespace-nowrap text-xl font-semibold tracking-tight sm:grow-0">
                            Organization Setup
                        </h1>
                    </div>
                    <div className="grid gap-4 md:grid-cols-[1fr_250px] lg:grid-cols-3 lg:gap-8">
                        <div className="grid auto-rows-max items-start gap-4 lg:col-span-2 lg:gap-8">
                            <Card>
                                <CardHeader>
                                    <CardTitle>Organization Details</CardTitle>
                                    <CardDescription>
                                        Provide your organization’s information below.
                                    </CardDescription>
                                </CardHeader>
                                <CardContent>
                                    <div className="grid gap-6">
                                        <div className="grid gap-3">
                                            <Label htmlFor="name">Organization Name</Label>
                                            <Input
                                                id="name"
                                                type="text"
                                                className="w-full"
                                                placeholder="Organization Name"
                                                value={data.name}
                                                onChange={(e) => setData('name', e.target.value)}
                                                required
                                            />
                                            <InputError message={errors.name} className="mt-2" />
                                        </div>
                                        <div className="grid gap-3">
                                            <Label htmlFor="description">Description</Label>
                                            <Textarea
                                                id="description"
                                                placeholder="Describe your organization"
                                                className="min-h-32"
                                                value={data.description}
                                                onChange={(e) => setData('description', e.target.value)}
                                            />
                                            <InputError message={errors.description} className="mt-2" />
                                        </div>
                                        <div className="grid gap-3">
                                            <Label htmlFor="email">Email</Label>
                                            <Input
                                                id="email"
                                                type="email"
                                                className="w-full"
                                                placeholder="Organization Email"
                                                value={data.email}
                                                onChange={(e) => setData('email', e.target.value)}
                                                required
                                            />
                                            <InputError message={errors.email} className="mt-2" />
                                        </div>
                                        <div className="grid gap-3">
                                            <Label htmlFor="phone">Phone Number</Label>
                                            <Input
                                                id="phone"
                                                type="tel"
                                                className="w-full"
                                                placeholder="Organization Phone"
                                                value={data.phone}
                                                onChange={(e) => setData('phone', e.target.value)}
                                                required
                                            />
                                            <InputError message={errors.phone} className="mt-2" />
                                        </div>
                                        <div className="grid gap-3">
                                            <Label htmlFor="website">Website</Label>
                                            <Input
                                                id="website"
                                                type="url"
                                                className="w-full"
                                                placeholder="Website URL"
                                                value={data.website}
                                                onChange={(e) => setData('website', e.target.value)}
                                            />
                                            <InputError message={errors.website} className="mt-2" />
                                        </div>
                                        <div className="grid gap-3">
                                            <Label htmlFor="category">Organization Category</Label>
                                            <Select
                                                value={data.category}
                                                onValueChange={(value) => setData('category', value)}
                                                required
                                            >
                                                <SelectTrigger id="type" aria-label="Select organization type">
                                                    <SelectValue placeholder="Select type" />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectItem value="corporate">Corporate</SelectItem>
                                                    <SelectItem value="non-profit">Non-Profit</SelectItem>
                                                    <SelectItem value="government">Government</SelectItem>
                                                </SelectContent>
                                            </Select>
                                            <InputError message={errors.category} className="mt-2" />
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>
                        </div>
                        <div className="grid auto-rows-max items-start gap-4 lg:gap-8">
                            <Card className="overflow-hidden">
                                <CardHeader>
                                    <CardTitle>Organization Logo</CardTitle>
                                    <CardDescription>
                                        Upload your organization's logo.
                                    </CardDescription>
                                </CardHeader>
                                <CardContent>
                                    <div className="grid gap-2">
                                        <img
                                            alt="Organization logo"
                                            className="aspect-square w-full rounded-md object-cover"
                                            height="300"
                                            src={data.logo ? URL.createObjectURL(data.logo) : '/images/placeholder.svg'}
                                            width="300"
                                        />
                                        <div className="grid w-full items-center gap-1.5">
                                            <Input
                                                id="logo"
                                                type="file"
                                                onChange={handleFileChange}
                                            />
                                            <InputError message={errors.logo} className="mt-2" />
                                        </div>
                                    </div>
                                </CardContent>
                            </Card>
                        </div>
                    </div>
                    <div className="flex items-center justify-center">
                        <Button type="submit" size="sm" disabled={processing}>
                            Save Organization
                        </Button>
                    </div>
                </div>
            </form>
        </>
    );
}