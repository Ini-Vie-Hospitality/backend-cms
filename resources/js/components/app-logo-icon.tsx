import type { ImgHTMLAttributes } from 'react';

export default function AppLogoIcon({
    alt = 'Ini Vie Hospitality',
    ...props
}: ImgHTMLAttributes<HTMLImageElement>) {
    return (
        <img {...props} src="/inivie-white.png" alt={alt} draggable={false} />
    );
}
