<script setup lang="ts">
import axios from 'axios';
import { reactive } from 'vue';
import { useRouter } from 'vue-router';
import { userAuth } from '../stores/index';
const authStore = userAuth();

const state = reactive({
  input : {
    email: '',
    password: ''
  },
  validation: {
      loading: false,
      error: {
          message: null
      }
  }
});

const router = useRouter();

const login = () => {
  state.validation.loading = true;

  axios.post(`${process.env.API_URL}/login`, {
      email: state.input.email,
      password: state.input.password,
  })
  .then((response) => {
    localStorage.setItem("userId", response.data.user_id);
    localStorage.setItem("accessToken", response.data.access_token);

    router.push({name: "dashboard"});
  })
  .catch((error) => {
      state.validation.loading = false;
      state.validation.error.message = error.response.data.message;
  });
}
</script>

<template>
  <div class="body-wrapper">
    <div class="page-wrapper">
      <main class="content-wrapper auth-screen">
        <div class="mdc-layout-grid">
					<div class="mdc-layout-grid__inner">
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4">
						</div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4">
							<div class="mdc-card">
								<section class="mdc-card__primary bg-white">
                  <form @submit.prevent="login">
                    <div class="mdc-layout-grid">
                      <p v-show="state.validation.error" class="mdc-theme--secondary text">{{ state.validation.error.message }}</p>
            					<div class="mdc-layout-grid__inner">
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                          <label class="mdc-text-field w-100">
                            <input v-model="state.input.email" placeholder="Email Address" type="text" class="mdc-text-field__input">
                            <!-- <span class="mdc-text-field__label">Email Address</span> -->
                            <div class="mdc-text-field__bottom-line"></div>
                          </label>
            						</div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                          <label class="mdc-text-field w-100">
                            <input v-model="state.input.password" placeholder="Password" type="password" class="mdc-text-field__input">
                            <!-- <span class="mdc-text-field__label">Password</span> -->
                            <div class="mdc-text-field__bottom-line"></div>
                          </label>
            						</div>
                        <!-- <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6">
                          <div class="mdc-form-field">
                            <div class="mdc-checkbox">
                              <input type="checkbox" id="my-checkbox" class="mdc-checkbox__native-control"/>
                              <div class="mdc-checkbox__background">
                                <svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
                                  <path class="mdc-checkbox__checkmark__path" fill="none" stroke="white" d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
                                </svg>
                                <div class="mdc-checkbox__mixedmark"></div>
                              </div>
                            </div>
                            <label for="my-checkbox">Remember me</label>
                          </div>
                        </div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-6 d-flex align-item-center justify-content-end">
                          <a href="#">Forgot Password</a>
                        </div> -->
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                          <button :disabled="state.validation.loading" type="submit" class="mdc-button mdc-button--raised w-100" data-mdc-auto-init="MDCRipple">
                            <template v-if="state.validation.loading">
                              Loading...
                            </template>
                            <template v-else>
                              Login
                            </template>
                          </button>
                        </div>
            					</div>
            				</div>
                  </form>
								</section>
							</div>
						</div>
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4">
						</div>
					</div>
				</div>
      </main>
    </div>
  </div>
</template>

<style scoped>
.body-wrapper .page-wrapper .content-wrapper {
    padding: 100px 0px 0px 0px;
}
</style>