import { Head } from '@inertiajs/react';
// import { Form, Head, router } from '@inertiajs/react';
// import InputError from '@/components/input-error';
// import PasswordInput from '@/components/password-input';
// import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
// import { Input } from '@/components/ui/input';
// import { Label } from '@/components/ui/label';
// import { Spinner } from '@/components/ui/spinner';
// import { login } from '@/routes';
// import { store } from '@/routes/register';
// import { Separator } from '@/components/ui/separator';

type Props = {
    passwordRules: string;
};

export default function Register({ passwordRules }: Props) {
    const handleGoogle = () => {
        window.location.href = '/auth/google/redirect';
    };

    return (
        <>
            <Head title="Register" />

            <Button
                className="cursor-pointer"
                onClick={handleGoogle}
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    x="0px"
                    y="0px"
                    width="24"
                    height="24"
                    viewBox="0 0 48 48"
                >
                    <path
                        fill="#FFC107"
                        d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"
                    ></path>
                    <path
                        fill="#FF3D00"
                        d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"
                    ></path>
                    <path
                        fill="#4CAF50"
                        d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"
                    ></path>
                    <path
                        fill="#1976D2"
                        d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"
                    ></path>
                </svg>{' '}
                Login Via Google
            </Button>

            {/*<div className="relative my-6">*/}
            {/*    <div className="absolute inset-0 flex items-center">*/}
            {/*        <Separator className="w-full" />*/}
            {/*    </div>*/}
            {/*    <div className="relative flex justify-center text-xs uppercase">*/}
            {/*        <span className="bg-background px-2 text-muted-foreground">*/}
            {/*            Or continue with email*/}
            {/*        </span>*/}
            {/*    </div>*/}
            {/*</div>*/}

            {/*<Form*/}
            {/*    {...store.form()}*/}
            {/*    resetOnSuccess={['password', 'password_confirmation']}*/}
            {/*    disableWhileProcessing*/}
            {/*    className="flex flex-col gap-6"*/}
            {/*>*/}
            {/*    {({ processing, errors }) => (*/}
            {/*        <>*/}
            {/*            <div className="grid gap-6">*/}
            {/*                <div className="grid gap-2">*/}
            {/*                    <Label htmlFor="name">Name</Label>*/}
            {/*                    <Input*/}
            {/*                        id="name"*/}
            {/*                        type="text"*/}
            {/*                        required*/}
            {/*                        autoFocus*/}
            {/*                        tabIndex={1}*/}
            {/*                        autoComplete="name"*/}
            {/*                        name="name"*/}
            {/*                        placeholder="Full name"*/}
            {/*                    />*/}
            {/*                    <InputError*/}
            {/*                        message={errors.name}*/}
            {/*                        className="mt-2"*/}
            {/*                    />*/}
            {/*                </div>*/}

            {/*                <div className="grid gap-2">*/}
            {/*                    <Label htmlFor="username">Username</Label>*/}
            {/*                    <Input*/}
            {/*                        id="username"*/}
            {/*                        type="text"*/}
            {/*                        required*/}
            {/*                        tabIndex={2}*/}
            {/*                        name="username"*/}
            {/*                        placeholder="username"*/}
            {/*                        pattern="[A-Za-z0-9_-]+"*/}
            {/*                        title="Only letters, numbers, underscores and dashes are allowed"*/}
            {/*                        onKeyDown={(e) => {*/}
            {/*                            if (*/}
            {/*                                e.ctrlKey ||*/}
            {/*                                e.metaKey ||*/}
            {/*                                e.key.length > 1*/}
            {/*                            ) {*/}
            {/*                                return;*/}
            {/*                            }*/}

            {/*                            if (!/^[A-Za-z0-9_-]$/.test(e.key)) {*/}
            {/*                                e.preventDefault();*/}
            {/*                            }*/}
            {/*                        }}*/}
            {/*                    />*/}
            {/*                    <InputError message={errors.username} />*/}
            {/*                </div>*/}

            {/*                <div className="grid gap-2">*/}
            {/*                    <Label htmlFor="email">Email address</Label>*/}
            {/*                    <Input*/}
            {/*                        id="email"*/}
            {/*                        type="email"*/}
            {/*                        required*/}
            {/*                        tabIndex={3}*/}
            {/*                        autoComplete="email"*/}
            {/*                        name="email"*/}
            {/*                        placeholder="email@example.com"*/}
            {/*                    />*/}
            {/*                    <InputError message={errors.email} />*/}
            {/*                </div>*/}

            {/*                <div className="grid gap-2">*/}
            {/*                    <Label htmlFor="password">Password</Label>*/}
            {/*                    <PasswordInput*/}
            {/*                        id="password"*/}
            {/*                        required*/}
            {/*                        tabIndex={4}*/}
            {/*                        autoComplete="new-password"*/}
            {/*                        name="password"*/}
            {/*                        placeholder="Password"*/}
            {/*                        passwordrules={passwordRules}*/}
            {/*                        onKeyDown={(e) => {*/}
            {/*                            if (*/}
            {/*                                e.ctrlKey ||*/}
            {/*                                e.metaKey ||*/}
            {/*                                e.key.length > 1*/}
            {/*                            ) {*/}
            {/*                                return;*/}
            {/*                            }*/}

            {/*                            if (*/}
            {/*                                !/^[A-Za-z0-9!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?~`]$/.test(*/}
            {/*                                    e.key,*/}
            {/*                                )*/}
            {/*                            ) {*/}
            {/*                                e.preventDefault();*/}
            {/*                            }*/}
            {/*                        }}*/}
            {/*                    />*/}
            {/*                    <InputError message={errors.password} />*/}
            {/*                </div>*/}

            {/*                <div className="grid gap-2">*/}
            {/*                    <Label htmlFor="password_confirmation">*/}
            {/*                        Confirm password*/}
            {/*                    </Label>*/}
            {/*                    <PasswordInput*/}
            {/*                        id="password_confirmation"*/}
            {/*                        required*/}
            {/*                        tabIndex={5}*/}
            {/*                        autoComplete="new-password"*/}
            {/*                        name="password_confirmation"*/}
            {/*                        placeholder="Confirm password"*/}
            {/*                        passwordrules={passwordRules}*/}
            {/*                        onKeyDown={(e) => {*/}
            {/*                            if (*/}
            {/*                                e.ctrlKey ||*/}
            {/*                                e.metaKey ||*/}
            {/*                                e.key.length > 1*/}
            {/*                            ) {*/}
            {/*                                return;*/}
            {/*                            }*/}

            {/*                            if (*/}
            {/*                                !/^[A-Za-z0-9!@#$%^&*()_+\-=[\]{};':"\\|,.<>/?~`]$/.test(*/}
            {/*                                    e.key,*/}
            {/*                                )*/}
            {/*                            ) {*/}
            {/*                                e.preventDefault();*/}
            {/*                            }*/}
            {/*                        }}*/}
            {/*                    />*/}
            {/*                    <InputError*/}
            {/*                        message={errors.password_confirmation}*/}
            {/*                    />*/}
            {/*                </div>*/}

            {/*                <Button*/}
            {/*                    type="submit"*/}
            {/*                    className="mt-2 w-full"*/}
            {/*                    tabIndex={6}*/}
            {/*                    data-test="register-user-button"*/}
            {/*                >*/}
            {/*                    {processing && <Spinner />}*/}
            {/*                    Create account*/}
            {/*                </Button>*/}
            {/*            </div>*/}

            {/*            <div className="text-center text-sm text-muted-foreground">*/}
            {/*                Already have an account?{' '}*/}
            {/*                <TextLink href={login()} tabIndex={7}>*/}
            {/*                    Log in*/}
            {/*                </TextLink>*/}
            {/*            </div>*/}
            {/*        </>*/}
            {/*    )}*/}
            {/*</Form>*/}
        </>
    );
}

Register.layout = {
    title: 'Create an account',
    description: 'Enter your details below to create your account',
};
