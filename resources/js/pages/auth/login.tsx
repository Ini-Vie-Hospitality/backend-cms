import { Form, Head } from '@inertiajs/react';
import { ArrowLeft, ArrowRight, LockKeyhole } from 'lucide-react';
import InputError from '@/components/input-error';
import PasskeyVerify from '@/components/passkey-verify';
import PasswordInput from '@/components/password-input';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { home } from '@/routes';
import { store } from '@/routes/login';
import { request } from '@/routes/password';

type Props = {
    status?: string;
    canResetPassword: boolean;
};

const fieldClassName =
    'h-[53px] rounded-[5px] border-[#d7ccbd] bg-[#fcfaf7] px-4 text-[0.95rem] text-[#302a24] shadow-none placeholder:text-[#9b9186] focus-visible:border-[#b99257] focus-visible:ring-[#b99257]/15';

export default function Login({ status, canResetPassword }: Props) {
    return (
        <>
            <Head title="Sign in to CMS" />
            <PasskeyVerify />

            {status && (
                <div className="mb-6 rounded-md border border-success/20 bg-success-muted px-4 py-3 text-sm font-medium text-success">
                    {status}
                </div>
            )}

            <Form
                {...store.form()}
                resetOnSuccess={['password']}
                className="flex flex-col"
            >
                {({ processing, errors }) => (
                    <>
                        <div className="grid gap-7">
                            <div className="grid gap-2.5">
                                <Label
                                    htmlFor="email"
                                    className="text-[0.94rem] font-medium text-[#342d26]"
                                >
                                    Email Address
                                </Label>
                                <Input
                                    id="email"
                                    type="email"
                                    name="email"
                                    required
                                    autoFocus
                                    tabIndex={1}
                                    autoComplete="email"
                                    placeholder="admin@inivie.com"
                                    className={fieldClassName}
                                />
                                <InputError message={errors.email} />
                            </div>

                            <div className="grid gap-2.5">
                                <Label
                                    htmlFor="password"
                                    className="text-[0.94rem] font-medium text-[#342d26]"
                                >
                                    Password
                                </Label>
                                <PasswordInput
                                    id="password"
                                    name="password"
                                    required
                                    tabIndex={2}
                                    autoComplete="current-password"
                                    placeholder="Password"
                                    className={fieldClassName}
                                />
                                <InputError message={errors.password} />
                            </div>

                            <div className="flex items-center justify-between gap-4">
                                <div className="flex items-center gap-3">
                                    <Checkbox
                                        id="remember"
                                        name="remember"
                                        tabIndex={3}
                                        className="size-5 border-[#cdbfae] data-[state=checked]:border-[#b07c2a] data-[state=checked]:bg-[#b07c2a] data-[state=checked]:text-white"
                                    />
                                    <Label
                                        htmlFor="remember"
                                        className="text-sm font-normal text-[#655b51]"
                                    >
                                        Remember me
                                    </Label>
                                </div>
                                {canResetPassword && (
                                    <TextLink
                                        href={request()}
                                        className="text-sm font-medium text-[#a56f22] no-underline hover:text-[#825716]"
                                        tabIndex={4}
                                    >
                                        Forgot password?
                                    </TextLink>
                                )}
                            </div>

                            <Button
                                type="submit"
                                className="mt-3 h-[60px] rounded-[5px] bg-[#29231d] text-base text-white hover:bg-[#3a322a]"
                                tabIndex={5}
                                disabled={processing}
                                data-test="login-button"
                            >
                                {processing ? (
                                    <Spinner />
                                ) : (
                                    <span className="w-4" />
                                )}
                                <span className="flex-1">Sign In</span>
                                <ArrowRight className="size-5" />
                            </Button>
                        </div>

                        <div className="mt-6 flex items-center justify-center gap-3 text-center text-[0.8rem] text-[#82786e]">
                            <LockKeyhole className="size-4" />
                            <span>
                                Secure access for authorized Ini Vie Hospitality
                                staff.
                            </span>
                        </div>

                        <TextLink
                            href={home()}
                            tabIndex={6}
                            className="mx-auto mt-8 flex w-fit items-center gap-3 text-sm font-medium text-[#a56f22] no-underline hover:text-[#825716]"
                        >
                            <ArrowLeft className="size-4" />
                            Back to Ini Vie Hospitality
                        </TextLink>
                    </>
                )}
            </Form>
        </>
    );
}

Login.layout = {
    title: 'Sign in to CMS',
    description: 'Access the Ini Vie Hospitality content management workspace.',
};
