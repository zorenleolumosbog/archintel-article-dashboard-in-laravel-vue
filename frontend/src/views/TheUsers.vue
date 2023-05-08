<script setup lang="ts">
import TheFormModal from '@/components/TheFormModal.vue';
import { onMounted, reactive } from "vue";
import axios from 'axios';
import { useRouter } from 'vue-router';
import useDate from '@/hooks/index';
import { userAuth } from '@/stores/index';
const authStore = userAuth();
const router = useRouter();

const state = reactive({
  formTitle: '',
  input: {
    firstName: '',
    lastName: '',
    email: '',
    password: '',
    passwordConfirmation: '',
    type: '',
    status: '',
  },
  selectedRecord: null,
  records: null,
  validation: {
    showModal: false,
    saving: false,
    errors: []
  }
});

onMounted(() => {
  getRecords();
});

const getLocaleDateString = (dateString: string) => {
  const { toLocaleDateString } = useDate(dateString);

  return toLocaleDateString.value;
};

const getRecords = () => {
  state.records = null;

  axios.get(`${process.env.API_URL}/users`, {
    headers: {
        Authorization: `Bearer ${authStore.accessToken}`,
    }
  })
  .then((response) => {
    state.records = response.data.data;
  })
  .catch((error) => {
    router.push({name: 'dashboard'});
  });
};

const add = () => {
  state.formTitle = 'Add New User';
  state.validation.showModal = true;
  state.validation.errors = [];
};

const edit = (id: string) => {
  state.formTitle = 'Edit User';
  state.validation.showModal = true;
  state.validation.errors = [];

  axios.get(`${process.env.API_URL}/users/${id}`, {
    headers: {
        Authorization: `Bearer ${authStore.accessToken}`,
    }
  })
  .then((response) => {
    state.selectedRecord = response.data.data;
    
    state.input = {
      firstName: state.selectedRecord?.firstname,
      lastName: state.selectedRecord?.lastname,
      email: state.selectedRecord?.email,
      password: '',
      passwordConfirmation: '',
      type: state.selectedRecord?.type,
      status: state.selectedRecord?.status,
    }
  })
};

const save = () => {
  state.validation.saving = true;

  axios.post(`${process.env.API_URL}/users`, {
    firstname: state.input.firstName,
    lastname: state.input.lastName,
    email: state.input.email,
    password: state.input.password,
    password_confirmation: state.input.passwordConfirmation,
    type: state.input.type,
    status: state.input.status,
  }, 
  {
    headers: {
        Authorization: `Bearer ${authStore.accessToken}`,
    }
  })
  .then((response) => {
    clear();
    getRecords();
  })
  .catch((error) => {
    state.validation.saving = false;
    state.validation.errors = error.response.data.errors;
  });
};

const update = () => {
  state.validation.saving = true;

  axios.put(`${process.env.API_URL}/users/${state.selectedRecord?.id}`, {
    firstname: state.input.firstName,
    lastname: state.input.lastName,
    email: state.input.email,
    password: state.input.password,
    password_confirmation: state.input.passwordConfirmation,
    type: state.input.type,
    status: state.input.status,
  }, 
  {
    headers: {
        Authorization: `Bearer ${authStore.accessToken}`,
    }
  })
  .then((response) => {
    clear();
    getRecords();
  })
  .catch((error) => {
    state.validation.saving = false;
    state.validation.errors = error.response.data.errors;
  });
};

const cancel = () => {
  clear();
};

const clear = () => {
  state.formTitle;
  state.input = {
    firstName: '',
    lastName: '',
    email: '',
    password: '',
    passwordConfirmation: '',
    type: '',
    status: '',
  };
  state.selectedRecord = null,
  state.validation = {
    showModal: false,
    saving: false,
    errors: []
  };
};
</script>

<template>
    <div class="mdc-layout-grid">
      <div class="mdc-layout-grid__inner">
        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
          <div class="mdc-card table-responsive">
            <div class="table-heading px-2 px-1 border-bottom">
              <h1 class="mdc-card__title mdc-card__title--large">Manage Users</h1>
            </div>
            <div class="mdc-layout-grid">
              <div class="mdc-layout-grid__inner">
                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-4">
                  <button class="mdc-button mdc-button--raised" @click="add">
                    <span class="mdc-button__label">Add New</span>
                  </button>
                </div>
                <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-8">
                  <input type="text" class="form-control" placeholder="Search">
                  <input type="date" class="form-control" placeholder="Filter Date Start">
                  <input type="date" class="form-control" placeholder="Filter Date End">
                </div>
              </div>
            </div>
            <table class="table">
              <thead>
                <tr>
                  <th class="text-left">Action</th>
                  <th class="text-left">First Name</th>
                  <th class="text-left">Last Name</th>
                  <th class="text-left">Type</th>
                  <th class="text-left">Status</th>
                  <th class="text-left">Date Created</th>
                </tr>
              </thead>
              <tbody>
                <template v-if="state.records">
                  <tr v-for="record in state.records" :key="record">
                      <td class="text-left">
                        <div @click="edit(record?.id)" class="col mdc-button" data-mdc-auto-init="MDCRipple">
                          <span class="material-symbols-outlined">edit_document</span>
                        </div>
                      </td>
                      <td class="text-left">{{ record?.firstname }}</td>
                      <td class="text-left">{{ record?.lastname }}</td>
                      <td class="text-left">{{ record?.type }}</td>
                      <td class="text-left">{{ record?.status }}</td>
                      <td class="text-left">{{ getLocaleDateString(record?.created_at) }}</td>
                  </tr>
                </template>
                <template v-else>
                  <tr>
                    <td colspan="3" class="text-center"><h3>Loading...</h3></td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <the-form-modal v-if="state.validation.showModal" 
    :formTitle="state.formTitle" 
    :selectedRecord="state.selectedRecord"
    :validation="state.validation" 
    @save="save" 
    @update="update" 
    @cancel="cancel">
      <div class="mdc-layout-grid__inner">
          <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-12">
              <label class="mdc-text-field w-100">
                  <input v-model="state.input.email" placeholder="Email Address" type="text" class="mdc-text-field__input">
                  <p v-for="(error, key) in state.validation.errors?.email" :key="key" class="mdc-theme--secondary text">
                    <strong>{{ error }}</strong>
                  </p>
              </label>
          </div>
          <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
              <label class="mdc-text-field w-100">
                  <input v-model="state.input.firstName" placeholder="First Name" type="text" class="mdc-text-field__input">
                  <p v-for="(error, key) in state.validation.errors?.firstname" :key="key" class="mdc-theme--secondary text">
                    <strong>{{ error }}</strong>
                  </p>
              </label>
          </div>
          <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
              <label class="mdc-text-field w-100">
                  <input v-model="state.input.lastName" placeholder="Last Name" type="text" class="mdc-text-field__input">
                  <p v-for="(error, key) in state.validation.errors?.lastname" :key="key" class="mdc-theme--secondary text">
                    <strong>{{ error }}</strong>
                  </p>
              </label>
          </div>
          <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
              <label class="mdc-text-field w-100">
                  <input v-model="state.input.password" placeholder="Password" type="password" class="mdc-text-field__input">
                  <p v-for="(error, key) in state.validation.errors?.password" :key="key" class="mdc-theme--secondary text">
                    <strong>{{ error }}</strong>
                  </p>
              </label>
          </div>
          <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
              <label class="mdc-text-field w-100">
                  <input v-model="state.input.passwordConfirmation" placeholder="Confirm Password" type="password" class="mdc-text-field__input">
              </label>
          </div>
          <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
            <label class="mdc-text-field w-100">
                <select v-model="state.input.type" class="mdc-text-field__input">
                  <option value="" disabled>Select Type</option>
                  <option value="Writer">Writer</option>
                  <option value="Editor">Editor</option>
                </select>
                <p v-for="(error, key) in state.validation.errors?.type" :key="key" class="mdc-theme--secondary text">
                  <strong>{{ error }}</strong>
                </p>
            </label>
          </div>
          <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
            <label class="mdc-text-field w-100">
                <select v-model="state.input.status" class="mdc-text-field__input">
                  <option value="" disabled>Select Status</option>
                  <option value="Active">Active</option>
                  <option value="Inactive">Inactive</option>
                </select>
                <p v-for="(error, key) in state.validation.errors?.status" :key="key" class="mdc-theme--secondary text">
                  <strong>{{ error }}</strong>
                </p>
            </label>
          </div>
      </div>
    </the-form-modal>
</template>