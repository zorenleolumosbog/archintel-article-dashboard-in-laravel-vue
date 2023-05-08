<script setup lang="ts">
import axios from 'axios';
import { useRouter, RouterLink } from 'vue-router';
import { userAuth } from '../stores/index';
import { computed, onMounted, reactive } from 'vue';
const authStore = userAuth();

const router = useRouter();

const state = reactive({
  currentUser: null
});

onMounted(() => {
  axios.get(`${process.env.API_URL}/users/${authStore.userId}`, {
    headers: {
        Authorization: `Bearer ${authStore.accessToken}`,
    }
  })
  .then((response) => {
    authStore.setCurrentUser(response.data.data);
    state.currentUser = response.data.data;
  })
  .catch((error) => {
    router.push({name: 'login'});
  });
});

const name = computed(() => {
  if(state.currentUser)
  return state.currentUser?.firstname + " " + state.currentUser?.lastname 
});
</script>

<template>
    <aside class="mdc-persistent-drawer mdc-persistent-drawer--open">
        <nav class="mdc-persistent-drawer__drawer">
          <div class="mdc-persistent-drawer__toolbar-spacer">
            <span>{{ state.currentUser?.type }}</span>
          </div>
          <div class="mdc-list-item mdc-drawer-item person-name" href="#" data-toggle="expansionPanel" target-panel="ui-sub-menu">
            <a class="mdc-drawer-link" href="#">
              <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon person-icon" aria-hidden="true">person</i>
              {{ name }}
              <i class="mdc-drawer-arrow material-icons">arrow_drop_down</i>
            </a>
            <div class="mdc-expansion-panel" id="ui-sub-menu">
              <nav class="mdc-list mdc-drawer-submenu">
                <div class="mdc-list-item mdc-drawer-item">
                  <a class="mdc-drawer-link" href="pages/ui-features/buttons.html">
                    Buttons
                  </a>
                </div>
                <div class="mdc-list-item mdc-drawer-item">
                  <a class="mdc-drawer-link" href="pages/ui-features/typography.html">
                    Typography
                  </a>
                </div>
              </nav>
            </div>
          </div>
          <div class="mdc-list-group">
            <nav class="mdc-list mdc-drawer-menu">
              <div class="mdc-list-item mdc-drawer-item">
                <RouterLink class="mdc-drawer-link" to="/dashboard">
                  <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">desktop_mac</i>
                  Dashboard
                </RouterLink>
              </div>
              <template v-if="state.currentUser?.type === 'Editor'">
                <div class="mdc-list-item mdc-drawer-item">
                  <RouterLink class="mdc-drawer-link" to="/users">
                    <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">person</i>
                    Users
                  </RouterLink>
                </div>
                <div class="mdc-list-item mdc-drawer-item">
                  <RouterLink class="mdc-drawer-link" to="/companies">
                    <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">groups</i>
                    Companies
                  </RouterLink>
                </div>
              </template>
              <div class="mdc-list-item mdc-drawer-item">
                <RouterLink class="mdc-drawer-link" to="/articles">
                  <i class="material-icons mdc-list-item__start-detail mdc-drawer-item-icon" aria-hidden="true">assignment</i>
                  All Media
                </RouterLink>
              </div>
            </nav>
          </div>
        </nav>
    </aside>
</template>

<style scoped>
.person-name {
  background-color: #00695c;
  border-bottom: 0.1rem solid #799591;
}
.person-icon {
  font-size: 38px !important;
  margin-top: -16px;
  margin-left: -6px;
}
</style>