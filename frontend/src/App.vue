<script setup>
import TheSidebar from '@/components/TheSidebar.vue'
import TheHeading from '@/components/TheHeading.vue'
import { RouterLink, RouterView, useRoute } from 'vue-router'

const route = useRoute();
</script>

<template>
  <div v-if="route.name === 'login' || route.name === 'register'">
    <RouterView />
  </div>
  <div v-else class="body-wrapper">
    <the-sidebar></the-sidebar>
    <the-heading></the-heading>
    <div class="page-wrapper mdc-toolbar-fixed-adjust">
      <main class="content-wrapper">
        <RouterView />
      </main>
    </div>
  </div>
</template>
