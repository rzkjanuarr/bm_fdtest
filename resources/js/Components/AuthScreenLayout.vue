<template>
  <div class="canvas canvas--auth">
    <Link href="/" class="brand auth-brand">
      <div class="brand__icon" style="background: white; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-family: var(--font-display); font-size: 28px; font-weight: 800; color: var(--color-primary);">
        B
      </div>
      <span class="brand__name">Book Management</span>
    </Link>
    <div class="auth-layout">
      <div class="auth-photo-wrap" aria-hidden="true">
        <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?w=1920&h=1080&fit=crop&q=80" alt="" class="auth-photo" />
        <div class="auth-photo-overlay">
          <div class="auth-photo-content">

            <div class="auth-photo-text">
              <p class="auth-photo-text-small">- Book Management</p>
              <h2 class="auth-photo-text-large">Pantau & kendalikan lini Office, kapan saja.</h2>
            </div>
          </div>
        </div>
      </div>
      <div class="auth-form-wrap"><slot /></div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>
