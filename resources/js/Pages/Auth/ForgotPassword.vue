<script setup>
import AuthScreenLayout from '@/Components/AuthScreenLayout.vue';
import PasswordInput from '@/Components/PasswordInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useToast } from '@/composables/useToast.js';

const toast = useToast();

const form = useForm({
    email: '',
    password: '',
});

const submit = () => {
    form.post(route('password.email'), {
        onFinish: () => {
            form.reset();
        },
        onSuccess: () => {
            toast.success('Password berhasil di-update, silahkan Login kembali!');
        },
        onError: (errors) => {
            console.log('Errors:', errors);
            if (errors.email) {
                toast.error('Maaf Email Anda belum terdaftar di Sistem Kami');
            }
            if (errors.password) {
                toast.error('Password harus minimal 6 karakter');
            }
        },
    });
};
</script>

<template>
    <AuthScreenLayout>
        <Head title="Lupa Password" />

        <form class="auth-form" @submit.prevent="submit">
            <header class="auth-form__head">
                <h1 class="auth-form__title">Lupa Password</h1>
                <p class="auth-form__subtitle">Masukkan email Anda yang terdaftar dan kata sandi baru!</p>
            </header>

            <label class="auth-field">
                <input type="email" name="email" autocomplete="email" placeholder="example@email.com" v-model="form.email" required autofocus />
            </label>

            <PasswordInput name="password" placeholder="Kata sandi baru" ariaLabel="Tampilkan password" v-model="form.password" required autocomplete="new-password" />

            <button type="submit" class="auth-submit" :disabled="form.processing" :aria-busy="form.processing">
                {{ form.processing ? 'Memperbarui...' : 'Perbarui Kata Sandi' }}
            </button>

            <p class="auth-switch">
                <span class="auth-switch__text">
                    <Link :href="route('login')" class="auth-switch__link">Kembali ke Login</Link>
                </span>
            </p>
        </form>
    </AuthScreenLayout>
</template>
