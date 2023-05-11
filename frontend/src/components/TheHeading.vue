<script setup lang="ts">
import axios from 'axios';
import { RouterLink } from 'vue-router';
import { useRouter } from 'vue-router';
import { userAuth } from '../stores/index';
const authStore = userAuth();
const router = useRouter();

const logout = () => {
  axios.post(`${process.env.API_URL}/logout`, {}, 
  {
    headers: {
        Authorization: `Bearer ${localStorage.getItem("accessToken")}`,
    }
  })
  .then((response) => {
    authStore.setCurrentUser(null);
    localStorage.removeItem("userId");
    localStorage.removeItem("accessToken");
  });
  
  router.push({name: "login"});
}
</script>

<template>
    <header class="mdc-toolbar mdc-elevation--z4 mdc-toolbar--fixed">
        <div class="mdc-toolbar__row">
          <section class="mdc-toolbar__section mdc-toolbar__section--align-start">
            <a href="javascript:void(0)" class="menu-toggler material-icons mdc-toolbar__menu-icon">menu</a>
            <span class="mdc-toolbar__input">
              <div class="mdc-text-field">
                <input type="text" class="mdc-text-field__input" id="css-only-text-field-box" placeholder="Search">
              </div>
            </span>
          </section>
          <section class="mdc-toolbar__section mdc-toolbar__section--align-end" role="toolbar">
            <div class="mdc-menu-anchor">
              <a href="javascript:void(0)" class="mdc-toolbar__icon toggle mdc-ripple-surface" data-toggle="dropdown" toggle-dropdown="notification-menu" data-mdc-auto-init="MDCRipple">
                <i class="material-icons">notifications</i>
                <span class="dropdown-count">3</span>
              </a>
              <div class="mdc-simple-menu mdc-simple-menu--right" tabindex="-1" id="notification-menu">
                <div class="table-heading pl-1 px-1 border-bottom">
                  <h1 class="mdc-card__title mdc-card__title">Notifications</h1>
                </div>
                <ul class="mdc-simple-menu__items mdc-list" role="menu" aria-hidden="true">
                  <li class="mdc-list-item border-bottom" role="menuitem" tabindex="0">
                    <i class="material-icons mdc-theme--primary mr-1">email</i>
                    One unread message
                  </li>
                  <li class="mdc-list-item border-bottom" role="menuitem" tabindex="0">
                    <i class="material-icons mdc-theme--primary mr-1">group</i>
                    One event coming up
                  </li>
                  <li class="mdc-list-item" role="menuitem" tabindex="0">
                    <i class="material-icons mdc-theme--primary mr-1">cake</i>
                    It's Aleena's birthday!
                  </li>
                </ul>
              </div>
            </div>
            <div class="mdc-menu-anchor">
              <a href="javascript:void(0)" class="mdc-toolbar__icon toggle mdc-ripple-surface" data-toggle="dropdown" toggle-dropdown="logout-menu" data-mdc-auto-init="MDCRipple">
                <i class="material-icons">settings</i>
              </a>
              <div class="mdc-simple-menu mdc-simple-menu--right" tabindex="-1" id="logout-menu">
                  <div class="table-heading pl-1 px-1 border-bottom">
                    <h1 class="mdc-card__title mdc-card__title">Settings</h1>
                  </div>
                  <section class="mdc-card__supporting-text">
                    <span>General</span>
                    <div class="mdc-switch pt-1">
                      <input type="checkbox" class="mdc-switch__native-control" />
                      <div class="mdc-switch__background">
                        <div class="mdc-switch__knob"></div>
                      </div>
                      <span class="pl-1">Push Notification</span>
                    </div>
                  </section>
                  <section class="mdc-card__supporting-text border-bottom pb-1">
                    <div class="mdc-switch">
                      <input type="checkbox" class="mdc-switch__native-control" />
                      <div class="mdc-switch__background">
                        <div class="mdc-switch__knob"></div>
                      </div>
                      <span class="pl-1">Dark Mode</span>
                    </div>
                  </section>
                  <ul class="mdc-simple-menu__items mdc-list" role="menu" aria-hidden="true">
                    <li @click="logout" class="mdc-list-item" role="menuitem" tabindex="0">
                      <i class="material-icons mdc-theme--primary mr-1">power_settings_new</i>
                      Logout
                    </li>
                  </ul>
              </div>
            </div>
            <div class="mdc-menu-anchor mr-1">
              <a href="javascript:void(0)" class="mdc-toolbar__icon toggle mdc-ripple-surface" data-toggle="dropdown" toggle-dropdown="history-menu" data-mdc-auto-init="MDCRipple">
                <i class="material-icons">history</i>
              </a>
              <div class="mdc-simple-menu mdc-simple-menu--right" tabindex="-1" id="history-menu">
                  <ul class="mdc-simple-menu__items mdc-list" role="menu" aria-hidden="true">
                    <li class="mdc-list-item" role="menuitem" tabindex="0">
                      <i class="material-icons mdc-theme--primary mr-1">history</i>
                      Revision here
                    </li>
                    <li class="mdc-list-item" role="menuitem" tabindex="0">
                      <i class="material-icons mdc-theme--primary mr-1">history</i>
                      Revision here
                    </li>
                  </ul>
              </div>
            </div>
          </section>
        </div>
    </header>
</template>

<style scoped>
.mdc-toolbar__section .mdc-simple-menu__items .mdc-list-item i {
    font-size: 1.8rem;
}
.mdc-switch {
  display: flex;
  align-items: center;
  justify-content: flex-start;
}
</style>