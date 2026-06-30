<script setup>
import AuthScreenLayout from '@/Components/AuthScreenLayout.vue';
import PasswordInput from '@/Components/PasswordInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <AuthScreenLayout>
        <Head title="Masuk" />

        <form class="auth-form" @submit.prevent="submit">
            <header class="auth-form__head">
                <h1 class="auth-form__title">Masuk</h1>
                <p class="auth-form__subtitle">Masuk dan jelajahi sekarang juga!</p>
            </header>
            <label class="auth-field">
                <input type="email" name="email" autocomplete="email" placeholder="example@email.com" v-model="form.email" required autofocus />
            </label>
            <PasswordInput name="password" placeholder="Password" ariaLabel="Tampilkan password" v-model="form.password" required autocomplete="current-password" />
            <div class="auth-forgot">
                <Link :href="route('password.request')" class="auth-switch__link">Lupa Password?</Link>
            </div>
            <button type="submit" class="auth-submit" :disabled="form.processing" :aria-busy="form.processing">
                {{ form.processing ? 'Masuk...' : 'Masuk' }}
            </button>
            <p class="auth-switch">
                <span class="auth-switch__text">
                    <span>Belum punya Akun?</span> <Link :href="route('register')" class="auth-switch__link">Daftar</Link>
                </span>
            </p>
        </form>
    </AuthScreenLayout>
</template>
