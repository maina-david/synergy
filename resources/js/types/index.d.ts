export interface ModuleType {
    id: string,
    name: string,
    description: string,
    url: string,
    icon: string,
    banner: string,
    subscription_type: string,
    price: number,
    active: boolean,
    is_subscribed: boolean
}

export interface CategoryType {
    id: string,
    name: string;
    description: string;
    slug: string;
    modules: ModuleType[];
}

export interface User {
    id: number;
    organization_id: number;
    honorific: string;
    first_name: string;
    middle_name?: string;
    last_name: string;
    email: string;
    phone: string;
    email_verified_at?: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
    moduleCategories: CategoryType[];
    modules: ModuleType[];
    appName: string;
};

export type CartItem = {
    id: string
    name: string
    quantity: number
    price: number
    type: string
}
