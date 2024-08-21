export interface ModuleType {
    is_subscribed: boolean;
    id: string,
    name: string,
    description: string,
    url: string,
    icon: string,
    banner: string,
    subscription_type: string,
    price: number,
    active: boolean
}
