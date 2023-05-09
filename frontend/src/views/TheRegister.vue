<script setup lang="ts">
import axios from 'axios';
import { reactive } from 'vue';
import { useRouter } from 'vue-router';
import { userAuth } from '../stores/index';
const authStore = userAuth();

const state = reactive({
  input : {
    email: '',
    firstname: '',
    lastname: '',
    password: '',
    passwordConfirmation: ''
  },
  validation: {
      loading: false,
      isSuccess: false,
      errors: []
  }
});

const router = useRouter();

const redirectToLogin = () => {
  router.push({name: "login"});
};

const register = () => {
  state.validation.loading = true;

  axios.post(`${process.env.API_URL}/register`, {
      email: state.input.email,
      firstname: state.input.firstname,
      lastname: state.input.lastname,
      password: state.input.password,
      password_confirmation: state.input.passwordConfirmation,
      type: "Writer",
      status: "Active"
  })
  .then((response) => {
    state.validation.isSuccess = true;
    state.input = {
      email: '',
      firstname: '',
      lastname: '',
      password: '',
      passwordConfirmation: ''
    };
    state.validation.loading = false;
  })
  .catch((error) => {
      state.validation.loading = false;
      state.validation.errors = error.response.data.errors;
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
                  <form @submit.prevent="register">
                    <div class="mdc-layout-grid">
                      <div class="mdc-layout-grid__inner">
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                          <p v-show="state.validation.isSuccess" class="mdc-theme--primary text">
                            <strong>Successfully Registered. <a href="javascript:void(0)" @click="redirectToLogin">Sign In Here</a></strong>
                          </p>
                        </div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                          <label class="mdc-text-field w-100">
                            <input v-model="state.input.email" placeholder="Email Address" type="text" class="mdc-text-field__input">
                            <!-- <span class="mdc-text-field__label">Email Address</span> -->
                            <div class="mdc-text-field__bottom-line"></div>
                            <p v-for="(error, key) in state.validation.errors?.email" :key="key" class="mdc-theme--secondary text">
                              <strong>{{ error }}</strong>
                            </p>
                          </label>
            						</div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                          <label class="mdc-text-field w-100">
                            <input v-model="state.input.firstname" placeholder="First Name" type="text" class="mdc-text-field__input">
                            <!-- <span class="mdc-text-field__label">Email Address</span> -->
                            <div class="mdc-text-field__bottom-line"></div>
                            <p v-for="(error, key) in state.validation.errors?.firstname" :key="key" class="mdc-theme--secondary text">
                              <strong>{{ error }}</strong>
                            </p>
                          </label>
            						</div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                          <label class="mdc-text-field w-100">
                            <input v-model="state.input.lastname" placeholder="Last Name" type="text" class="mdc-text-field__input">
                            <!-- <span class="mdc-text-field__label">Email Address</span> -->
                            <div class="mdc-text-field__bottom-line"></div>
                            <p v-for="(error, key) in state.validation.errors?.lastname" :key="key" class="mdc-theme--secondary text">
                              <strong>{{ error }}</strong>
                            </p>
                          </label>
            						</div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                          <label class="mdc-text-field w-100">
                            <input v-model="state.input.password" placeholder="Password" type="password" class="mdc-text-field__input">
                            <!-- <span class="mdc-text-field__label">Password</span> -->
                            <div class="mdc-text-field__bottom-line"></div>
                            <p v-for="(error, key) in state.validation.errors?.password" :key="key" class="mdc-theme--secondary text">
                              <strong>{{ error }}</strong>
                            </p>
                          </label>
            						</div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                          <label class="mdc-text-field w-100">
                            <input v-model="state.input.passwordConfirmation" placeholder="Confirm Password" type="password" class="mdc-text-field__input">
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
                              Register
                            </template>
                          </button>
                        </div>
                        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                          <p>Already have an account? <a href="javascript:void(0)" @click="redirectToLogin">Sign In Here</a></p>
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