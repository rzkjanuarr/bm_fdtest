<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});
</script>

<template>
    <Head title="Landing Page" />
    <div class="min-h-screen bg-gray-50 flex flex-col" style="background: #f9f9f9; min-height: 100vh; display: flex; flex-direction: column;">
        <!-- Navbar -->
        <header class="bg-white shadow-sm sticky top-0 z-50" style="background: white; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); position: sticky; top: 0; z-index: 50;">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" style="max-width: 1280px; margin: 0 auto; padding: 0 1rem;">
                <div class="flex justify-between items-center h-16" style="display: flex; justify-content: space-between; align-items: center; height: 4rem;">
                    <h1 class="text-2xl font-bold text-red-600" style="font-size: 1.5rem; font-weight: 700; color: #fe0006;">📚 Manajemen Buku</h1>
                    <nav class="flex items-center gap-4" style="display: flex; align-items: center; gap: 1rem;">
                        <Link v-if="canLogin" :href="route('login')" class="text-gray-700 hover:text-red-600 font-medium" style="color: #374151; font-weight: 500; text-decoration: none;" onmouseover="this.style.color='#fe0006'" onmouseout="this.style.color='#374151'">Login</Link>
                        <Link v-if="canRegister" :href="route('register')" class="px-6 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition" style="padding: 0.5rem 1.5rem; background: #fe0006; color: white; border-radius: 0.5rem; font-weight: 500; text-decoration: none;" onmouseover="this.style.background='#c70004'" onmouseout="this.style.background='#fe0006'">Daftar</Link>
                    </nav>
                </div>
            </div>
        </header>

        <!-- Hero -->
        <section class="flex-grow flex items-center justify-center" style="flex-grow: 1; display: flex; align-items: center; justify-content: center;">
            <div class="max-w-4xl mx-auto px-4 text-center" style="max-width: 64rem; margin: 0 auto; padding: 0 1rem; text-align: center;">
                <h2 class="text-5xl font-extrabold text-gray-900 mb-6" style="font-size: 3rem; font-weight: 800; color: #111827; margin-bottom: 1.5rem;">
                    Kelola Koleksi Buku Anda
                    <span class="text-red-600" style="color: #fe0006;">Dengan Mudah</span>
                </h2>
                <p class="text-xl text-gray-600 mb-10" style="font-size: 1.25rem; color: #4b5563; margin-bottom: 2.5rem;">Platform manajemen buku yang simple, cepat, dan modern</p>
                <div class="flex justify-center gap-4" style="display: flex; justify-content: center; gap: 1rem;">
                    <Link v-if="canRegister" :href="route('register')" class="px-8 py-4 bg-red-600 text-white text-lg font-semibold rounded-xl shadow-lg hover:bg-red-700 hover:shadow-xl transition-all" style="padding: 1rem 2rem; background: #fe0006; color: white; font-size: 1.125rem; font-weight: 600; border-radius: 0.75rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1); text-decoration: none;" onmouseover="this.style.background='#c70004'; this.style.boxShadow='0 20px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1)'" onmouseout="this.style.background='#fe0006'; this.style.boxShadow='0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -4px rgba(0,0,0,0.1)'">Mulai Sekarang</Link>
                    <Link v-if="canLogin" :href="route('login')" class="px-8 py-4 bg-white text-gray-700 text-lg font-semibold rounded-xl shadow border border-gray-200 hover:border-red-300 transition-all" style="padding: 1rem 2rem; background: white; color: #374151; font-size: 1.125rem; font-weight: 600; border-radius: 0.75rem; box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05); border: 1px solid #e5e7eb; text-decoration: none;" onmouseover="this.style.borderColor='#ff4448'" onmouseout="this.style.borderColor='#e5e7eb'">Masuk</Link>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 py-8" style="background: white; border-top: 1px solid #e5e7eb; padding: 2rem 0;">
            <div class="max-w-7xl mx-auto px-4 text-center" style="max-width: 1280px; margin: 0 auto; padding: 0 1rem; text-align: center;">
                <p class="text-gray-600" style="color: #4b5563;">© 2025 Manajemen Buku. All rights reserved.</p>
            </div>
        </footer>
    </div>
</template>