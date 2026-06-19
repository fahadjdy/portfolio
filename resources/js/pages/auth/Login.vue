<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { LoaderCircle, Eye, EyeOff, ArrowLeft, Check } from 'lucide-vue-next';
import { ref } from 'vue';

defineProps<{
    status?: string;
    canResetPassword: boolean;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const perks = [
    'Manage projects, blog & content',
    'Track leads and inquiries',
    'Site analytics at a glance',
];
</script>

<template>
    <Head title="Log in" />

    <div class="flex min-h-screen bg-white dark:bg-slate-950">
        <!-- Brand panel -->
        <div class="relative hidden w-1/2 flex-col justify-between overflow-hidden bg-gradient-to-br from-brand-600 via-brand-700 to-brand-900 p-12 text-white lg:flex">
            <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(rgba(255,255,255,0.5) 1px, transparent 1px); background-size: 22px 22px;"></div>
            <div class="absolute -bottom-24 -right-16 h-80 w-80 rounded-full bg-white/10 blur-3xl"></div>

            <a href="/" class="relative flex items-center gap-3">
                <span class="grid h-11 w-11 place-items-center rounded-xl bg-white/15 font-display text-lg font-bold backdrop-blur">FJ</span>
                <span class="font-display text-xl font-bold">Fahad Jadiya</span>
            </a>

            <div class="relative max-w-md">
                <h1 class="font-display text-4xl font-extrabold leading-tight">Welcome back.</h1>
                <p class="mt-4 text-lg text-white/80">Sign in to manage your portfolio — projects, blog, leads and analytics, all in one place.</p>
                <ul class="mt-8 space-y-3">
                    <li v-for="perk in perks" :key="perk" class="flex items-center gap-3 text-white/90">
                        <span class="grid h-6 w-6 place-items-center rounded-full bg-white/15"><Check class="h-3.5 w-3.5" /></span>
                        {{ perk }}
                    </li>
                </ul>
            </div>

            <p class="relative text-sm text-white/60">&copy; {{ new Date().getFullYear() }} Fahad Jadiya · Senior Full-Stack Developer</p>
        </div>

        <!-- Form panel -->
        <div class="flex w-full flex-col justify-center px-6 py-12 sm:px-12 lg:w-1/2">
            <div class="mx-auto w-full max-w-sm">
                <a href="/" class="mb-8 flex items-center gap-2 lg:hidden">
                    <span class="grid h-10 w-10 place-items-center rounded-xl bg-brand-600 font-display text-base font-bold text-white">FJ</span>
                    <span class="font-display text-lg font-bold text-slate-900 dark:text-white">Fahad Jadiya</span>
                </a>

                <h2 class="font-display text-2xl font-bold text-slate-900 dark:text-white">Sign in</h2>
                <p class="mt-1 text-sm text-slate-500">Enter your credentials to access the admin panel.</p>

                <div v-if="status" class="mt-4 rounded-lg bg-emerald-50 px-4 py-2.5 text-sm font-medium text-emerald-700">{{ status }}</div>

                <form @submit.prevent="submit" class="mt-8 space-y-5">
                    <div>
                        <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300">Email address</label>
                        <input id="email" type="email" required autofocus autocomplete="email" v-model="form.email" placeholder="you@example.com"
                               class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:focus:ring-brand-900" />
                        <p v-if="form.errors.email" class="mt-1 text-xs text-rose-600">{{ form.errors.email }}</p>
                    </div>

                    <div>
                        <div class="mb-1.5 flex items-center justify-between">
                            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300">Password</label>
                            <a v-if="canResetPassword" :href="route('password.request')" class="text-xs font-medium text-brand-700 hover:underline">Forgot password?</a>
                        </div>
                        <div class="relative">
                            <input id="password" :type="showPassword ? 'text' : 'password'" required autocomplete="current-password" v-model="form.password" placeholder="••••••••"
                                   class="w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 pr-10 text-sm text-slate-900 shadow-sm placeholder:text-slate-400 focus:border-brand-500 focus:outline-none focus:ring-2 focus:ring-brand-200 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 dark:focus:ring-brand-900" />
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 grid w-10 place-items-center text-slate-400 hover:text-slate-600" :aria-label="showPassword ? 'Hide password' : 'Show password'">
                                <EyeOff v-if="showPassword" class="h-4 w-4" />
                                <Eye v-else class="h-4 w-4" />
                            </button>
                        </div>
                        <p v-if="form.errors.password" class="mt-1 text-xs text-rose-600">{{ form.errors.password }}</p>
                    </div>

                    <label class="flex cursor-pointer items-center gap-2 text-sm text-slate-600 dark:text-slate-300">
                        <input type="checkbox" v-model="form.remember" class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500" />
                        Remember me
                    </label>

                    <button type="submit" :disabled="form.processing"
                            class="flex w-full items-center justify-center gap-2 rounded-lg bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700 disabled:opacity-60">
                        <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                        Sign in
                    </button>
                </form>

                <a href="/" class="mt-8 inline-flex items-center gap-1.5 text-sm font-medium text-slate-500 hover:text-brand-700">
                    <ArrowLeft class="h-4 w-4" /> Back to website
                </a>
            </div>
        </div>
    </div>
</template>
