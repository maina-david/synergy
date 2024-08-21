import { ScrollArea, ScrollBar } from '@/Components/ui/scroll-area';
import { Head } from '@inertiajs/react';
import { listenNowAlbums, madeForYouAlbums } from "@/Components/data/albums"
import { Separator } from '@/Components/ui/separator';
import { AlbumArtwork } from '@/Components/album-artwork';

export default function AllProducts() {
    return (
        <>
            <Head title='All Products' />
            <div className="flex items-center justify-between">
                <div className="space-y-1">
                    <h2 className="text-2xl font-semibold tracking-tight">
                        Listen Now
                    </h2>
                    <p className="text-sm text-muted-foreground">
                        Top picks for you. Updated daily.
                    </p>
                </div>
            </div>
            <Separator className="my-4" />
            <div className="relative">
                <ScrollArea>
                    <div className="flex space-x-4 pb-4">
                        {listenNowAlbums.map((album) => (
                            <AlbumArtwork
                                key={album.name}
                                album={album}
                                className="w-[250px]"
                                aspectRatio="portrait"
                                width={250}
                                height={330}
                            />
                        ))}
                    </div>
                    <ScrollBar orientation="horizontal" />
                </ScrollArea>
            </div>
            <div className="mt-6 space-y-1">
                <h2 className="text-2xl font-semibold tracking-tight">
                    Made for You
                </h2>
                <p className="text-sm text-muted-foreground">
                    Your personal playlists. Updated daily.
                </p>
            </div>
            <Separator className="my-4" />
            <div className="relative">
                <ScrollArea>
                    <div className="flex space-x-4 pb-4">
                        {madeForYouAlbums.map((album) => (
                            <AlbumArtwork
                                key={album.name}
                                album={album}
                                className="w-[150px]"
                                aspectRatio="square"
                                width={150}
                                height={150}
                            />
                        ))}
                    </div>
                    <ScrollBar orientation="horizontal" />
                </ScrollArea>
            </div>
        </>
    );
}
