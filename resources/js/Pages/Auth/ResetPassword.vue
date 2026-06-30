<script setup>
import AuthScreenLayout from '@/Components/AuthScreenLayout.vue';
import PasswordInput from '@/Components/PasswordInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps(['token', 'email']);

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <AuthScreenLayout>
        <Head title="Reset Password" />

        <form class="auth-form" @submit.prevent="submit">
            <header class="auth-form__head">
                <h1 class="auth-form__title">Reset Password</h1>
                <p class="auth-form__subtitle">Masukkan password baru Anda!</p>
            </header>

            <label class="auth-field">
                <input type="email" name="email" autocomplete="email" placeholder="example@email.com" v-model="form.email" required autofocus />
            </label>

            <PasswordInput name="password" placeholder="Password baru" ariaLabel="Tampilkan password" v-model="form.password" required autocomplete="new-password" />

            <PasswordInput name="password_confirmation" placeholder="Konfirmasi password baru" ariaLabel="Tampilkan password" v-model="form.password_confirmation" required autocomplete="new-password" />

            <button type="submit" class="auth-submit" :disabled="form.processing" :aria-busy="form.processing">
                {{ form.processing ? 'Menyimpan...' : 'Reset Password' }}
            </button>

            <p class="auth-switch">
                <span class="auth-switch__text">
                    <Link :href="route('login')" class="auth-switch__link">Kembali ke Login</Link>
                </span>
            </p>
        </form>
    </AuthScreenLayout>
</template>
