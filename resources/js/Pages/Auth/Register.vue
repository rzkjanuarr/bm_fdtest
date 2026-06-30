<script setup>
import AuthScreenLayout from '@/Components/AuthScreenLayout.vue';
import PasswordInput from '@/Components/PasswordInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthScreenLayout>
        <Head title="Daftar" />

        <form class="auth-form" @submit.prevent="submit">
            <header class="auth-form__head">
                <h1 class="auth-form__title">Daftar</h1>
                <p class="auth-form__subtitle">Gabung sekarang juga!</p>
            </header>
            <label class="auth-field">
                <input type="text" name="name" autocomplete="name" placeholder="Nama lengkap" v-model="form.name" required autofocus />
            </label>
            <label class="auth-field">
                <input type="email" name="email" autocomplete="email" placeholder="example@email.com" v-model="form.email" required />
            </label>
            <PasswordInput name="password" placeholder="Password" ariaLabel="Tampilkan password" v-model="form.password" required autocomplete="new-password" />
            <PasswordInput name="password_confirmation" placeholder="Konfirmasi Password" ariaLabel="Tampilkan password" v-model="form.password_confirmation" required autocomplete="new-password" />
            <button type="submit" class="auth-submit" :disabled="form.processing" :aria-busy="form.processing">
                {{ form.processing ? 'Daftar...' : 'Daftar' }}
            </button>
            <p class="auth-switch">
                <span class="auth-switch__text">
                    <span>Sudah punya Akun?</span> <Link :href="route('login')" class="auth-switch__link">Masuk</Link>
                </span>
            </p>
        </form>
    </AuthScreenLayout>
</template>
