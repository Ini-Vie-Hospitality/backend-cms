import { Form, Head } from '@inertiajs/react';
import { ArrowLeft, ArrowRight, LockKeyhole } from 'lucide-react';
import InputError from '@/components/input-error';
import PasswordInput from '@/components/password-input';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { login } from '@/routes';
import { store } from '@/routes/register';

type Props = {
    passwordRules: string;
};

const fieldClassName =
    'h-[44px] rounded-[5px] border-[#d7ccbd] bg-[#fcfaf7] px-4 text-[0.95rem] text-[#302a24] shadow-none placeholder:text-[#9b9186] focus-visible:border-[#b99257] focus-visible:ring-[#b99257]/15';

export default function Register({ passwordRules }: Props) {
    return (
        <>
            <Head title="Create your CMS account" />
            <Form
                {...store.form()}
                resetOnSuccess={['password', 'password_confirmation']}
                disableWhileProcessing
                className="flex flex-col"
            >
                {({ processing, errors }) => (
                    <>
                        <div className="grid gap-5">
                            <div className="grid gap-2">
                                <Label
                                    htmlFor="name"
                                    className="text-[0.9rem] font-medium text-[#342d26]"
                                >
                                    Full Name
                                </Label>
                                <Input
                                    id="name"
                                    type="text"
                                    required
                                    autoFocus
                                    tabIndex={1}
                                    autoComplete="name"
                                    name="name"
                                    placeholder="Your full name"
                                    className={fieldClassName}
                                />
                                <InputError message={errors.name} />
                            </div>

                            <div className="grid gap-2">
                                <Label
                                    htmlFor="email"
                                    className="text-[0.9rem] font-medium text-[#342d26]"
                                >
                                    Email Address
                                </Label>
                                <Input
                                    id="email"
                                    type="email"
                                    required
                                    tabIndex={2}
                                    autoComplete="email"
                                    name="email"
                                    placeholder="admin@inivie.com"
                                    className={fieldClassName}
                                />
                                <InputError message={errors.email} />
                            </div>

                            <div className="grid gap-2">
                                <Label
                                    htmlFor="password"
                                    className="text-[0.9rem] font-medium text-[#342d26]"
                                >
                                    Password
                                </Label>
                                <PasswordInput
                                    id="password"
                                    required
                                    tabIndex={3}
                                    autoComplete="new-password"
                                    name="password"
                                    placeholder="Password"
                                    passwordrules={passwordRules}
                                    className={fieldClassName}
                                />
                                <InputError message={errors.password} />
                            </div>

                            <div className="grid gap-2">
                                <Label
                                    htmlFor="password_confirmation"
                                    className="text-[0.9rem] font-medium text-[#342d26]"
                                >
                                    Confirm Password
                                </Label>
                                <PasswordInput
                                    id="password_confirmation"
                                    required
                                    tabIndex={4}
                                    autoComplete="new-password"
                                    name="password_confirmation"
                                    placeholder="Confirm password"
                                    passwordrules={passwordRules}
                                    className={fieldClassName}
                                />
                                <InputError
                                    message={errors.password_confirmation}
                                />
                            </div>

                            <Button
                                type="submit"
                                className="mt-2 h-[50px] rounded-[5px] bg-[#29231d] text-base text-white hover:bg-[#3a322a]"
                                tabIndex={5}
                                disabled={processing}
                                data-test="register-user-button"
                            >
                                {processing ? (
                                    <Spinner />
                                ) : (
                                    <span className="w-4" />
                                )}
                                <span className="flex-1">Create Account</span>
                                <ArrowRight className="size-5" />
                            </Button>
                        </div>

                        <div className="mt-5 flex items-center justify-center gap-3 text-center text-[0.8rem] text-[#82786e]">
                            <LockKeyhole className="size-4" />
                            <span>
                                Secure registration for authorized Ini Vie
                                Hospitality staff.
                            </span>
                        </div>

                        <TextLink
                            href={login()}
                            tabIndex={6}
                            className="mx-auto mt-6 flex w-fit items-center gap-3 text-sm font-medium text-[#a56f22] no-underline hover:text-[#825716]"
                        >
                            <ArrowLeft className="size-4" />
                            Back to Sign In
                        </TextLink>
                    </>
                )}
            </Form>
        </>
    );
}

Register.layout = {
    title: 'Create your CMS account',
    description: 'Join the Ini Vie Hospitality content management workspace.',
};
