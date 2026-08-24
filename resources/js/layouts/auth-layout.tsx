import { Link } from '@inertiajs/react';
import { ArrowUpRight } from 'lucide-react';
import AppLogoIcon from '@/components/app-logo-icon';
import { home } from '@/routes';

export default function AuthLayout({
    title = '',
    description = '',
    children,
}: {
    title?: string;
    description?: string;
    children: React.ReactNode;
}) {
    const isRegistration = title.toLowerCase().includes('create');
    const eyebrow = isRegistration ? 'JOIN INI VIE' : 'WELCOME BACK';

    return (
        <div className="min-h-svh bg-[#f8f5ef] text-[#2b251f] lg:grid lg:grid-cols-[51%_49%]">
            <aside className="relative hidden min-h-svh overflow-hidden bg-[#211d18] lg:block">
                <img
                    src="/auth-hero.png"
                    alt=""
                    className="absolute inset-0 h-full w-full object-cover"
                />
                <div className="absolute inset-0 bg-[linear-gradient(180deg,rgba(24,20,16,0.34)_0%,rgba(24,20,16,0.15)_42%,rgba(18,15,12,0.7)_100%)]" />

                <div className="relative z-10 flex h-full min-h-svh flex-col justify-between px-[6.5%] py-[7.5%] text-[#f7f1e8]">
                    <div className="flex items-center gap-10">
                        <Link href={home()} aria-label="Ini Vie Hospitality">
                            <AppLogoIcon className="h-auto w-[150px] brightness-0 hue-rotate-[350deg] invert saturate-[2.4] sepia" />
                        </Link>
                        <div className="h-14 w-px bg-[#c79a52]/75" />
                        <p className="text-[0.84rem] font-medium tracking-[0.28em] text-[#d6ad6b] uppercase">
                            Internal Content Management
                        </p>
                    </div>

                    <div className="max-w-[610px] border-l-[3px] border-[#c79a52] pl-6">
                        <p className="mb-4 text-[0.8rem] font-semibold tracking-[0.25em] text-[#d6ad6b] uppercase">
                            Digital Hospitality
                        </p>
                        <p className="max-w-[520px] font-serif text-[clamp(2.45rem,3vw,3.45rem)] leading-[0.98] tracking-[-0.035em] text-white">
                            Crafting remarkable
                            <br />
                            digital experiences.
                        </p>
                        <p className="mt-6 max-w-[520px] text-[1rem] leading-relaxed text-[#eee7dd]">
                            Manage the stories, properties, and experiences that
                            shape the Ini Vie Hospitality digital journey.
                        </p>
                    </div>
                </div>
            </aside>

            <section className="relative flex min-h-svh flex-col overflow-y-auto bg-[#f8f5ef] px-6 sm:px-10 lg:px-[clamp(3rem,6.7vw,7rem)]">
                <div className="flex items-center justify-between py-7 lg:justify-end lg:py-12">
                    <Link
                        href={home()}
                        className="lg:hidden"
                        aria-label="Ini Vie Hospitality"
                    >
                        <AppLogoIcon className="h-auto w-28 brightness-0" />
                    </Link>
                    <Link
                        href={home()}
                        className="group flex items-center gap-2 text-sm font-medium text-[#3a322a] transition-colors hover:text-[#a87327]"
                    >
                        View Public Website
                        <ArrowUpRight className="size-4 text-[#a87327] transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
                    </Link>
                </div>

                <div className="flex flex-1 items-center py-8 lg:py-10">
                    <div className="mx-auto w-full max-w-[550px]">
                        <header className="mb-11">
                            <p className="mb-3 text-[0.8rem] font-semibold tracking-[0.22em] text-[#a87327] uppercase">
                                {eyebrow}
                            </p>
                            <h1 className="font-serif text-[clamp(2.65rem,3vw,3.45rem)] leading-none font-normal tracking-[-0.035em] text-[#241f1a]">
                                {title}
                            </h1>
                            <p className="mt-4 text-[0.95rem] leading-relaxed text-[#786f65]">
                                {description}
                            </p>
                        </header>

                        {children}
                    </div>
                </div>

                <footer className="mx-auto w-full max-w-[550px] border-t border-[#d9d0c4] py-6 text-[0.78rem] leading-relaxed text-[#82786e] lg:py-7">
                    <p>
                        {'\u00a9'} {new Date().getFullYear()} Ini Vie
                        Hospitality
                    </p>
                    <p>Internal Content Management System</p>
                </footer>
            </section>
        </div>
    );
}
