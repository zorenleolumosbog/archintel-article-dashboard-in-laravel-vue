<script setup lang="ts">
import TheFormModal from '@/components/TheFormModal.vue';
import TheMessageModal from '@/components/TheMessageModal.vue';
import TheFormFilter from '@/components/TheFormFilter.vue';
import ThePagination from '@/components/ThePagination.vue';
import { onMounted, reactive, watch } from "vue";
import { useDebounceFn } from '@vueuse/core';
import axios from 'axios';
import { useRouter } from 'vue-router';
import hooks from '@/hooks/index';
const router = useRouter();

const state = reactive({
  inputFilter: {
      search: '',
      dateFrom: '',
      dateTo: ''
  },
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
  pagination: {
      current: 1,
      limit: 10
  },
  validation: {
    showModal: false,
    saving: false,
    isLoadingRecords: false,
    isSuccess: false,
    errors: []
  }
});

onMounted(() => {
  getRecords();
});

const debouncedRecords = useDebounceFn(() => {
  getRecords();
}, 1000);

const currentPage = (val: number) => {
    state.pagination.current = val;
    getRecords();
};

const getRecords = () => {
  state.validation.isLoadingRecords = true;

  axios.get(`${process.env.API_URL}/users`, {
    headers: {
        Authorization: `Bearer ${localStorage.getItem("accessToken")}`,
    },
    params: {
        page: state.pagination.current,
        limit: state.pagination.limit,
        search: state.inputFilter.search,
        date_from: state.inputFilter.dateFrom,
        date_to: state.inputFilter.dateTo
    }
  })
  .then((response) => {
    state.records = response.data;
    state.validation.isLoadingRecords = false;
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

  state.selectedRecord = hooks.useFilterRecord('id', state.records?.data, id);
    
  state.input = {
    firstName: state.selectedRecord?.firstname,
    lastName: state.selectedRecord?.lastname,
    email: state.selectedRecord?.email,
    password: '',
    passwordConfirmation: '',
    type: state.selectedRecord?.type,
    status: state.selectedRecord?.status,
  }
};

const store = () => {
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
        Authorization: `Bearer ${localStorage.getItem("accessToken")}`,
    }
  })
  .then((response) => {
    clear();
    getRecords();

    state.formTitle = "Successfully Added."
    state.validation.isSuccess = true;
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
        Authorization: `Bearer ${localStorage.getItem("accessToken")}`,
    }
  })
  .then((response) => {
    clear();
    getRecords();

    state.formTitle = "Successfully Updated."
    state.validation.isSuccess = true;
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
    isLoadingRecords: false,
    isSuccess: false,
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
                  <the-form-filter :inputFilter="state.inputFilter" @debouncedRecords="debouncedRecords"></the-form-filter>
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
                <template v-if="state.validation.isLoadingRecords">
                  <tr>
                    <td colspan="3" class="text-center"><h3>Loading...</h3></td>
                  </tr>
                </template>
                <template v-else>
                  <tr v-for="record in state.records?.data" :key="record">
                      <td class="text-left">
                        <div @click="edit(record?.id)" class="col mdc-button" data-mdc-auto-init="MDCRipple">
                          <span class="material-symbols-outlined">edit_document</span>
                        </div>
                      </td>
                      <td class="text-left">{{ record?.firstname }}</td>
                      <td class="text-left">{{ record?.lastname }}</td>
                      <td class="text-left">{{ record?.type }}</td>
                      <td class="text-left">{{ record?.status }}</td>
                      <td class="text-left">{{ hooks.useToLocaleDateString(record?.created_at).value }}</td>
                  </tr>
                </template>
              </tbody>
            </table>
            <the-pagination v-if="state.records?.data.length > 0" :records="state.records" :pagination="state.pagination" @getRecords="getRecords" @currentPage="currentPage"></the-pagination>
          </div>
        </div>
      </div>
    </div>
    <the-message-modal v-if="state.validation.isSuccess" :formTitle="state.formTitle" @cancel="cancel"></the-message-modal>
    <the-form-modal v-if="state.validation.showModal" 
    :formTitle="state.formTitle" 
    :selectedRecord="state.selectedRecord"
    :validation="state.validation" 
    @store="store" 
    @update="update" 
    @cancel="cancel">
      <div class="mdc-layout-grid__inner">
          <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-12">
              <label class="mdc-text-field w-100">
                <strong>Email Address</strong>
                <input v-model="state.input.email" type="text" class="mdc-text-field__input pt-1">
                <p v-for="(error, key) in state.validation.errors?.email" :key="key" class="mdc-theme--secondary text">
                  <strong>{{ error }}</strong>
                </p>
              </label>
          </div>
          <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
              <label class="mdc-text-field w-100">
                <strong>First Name</strong>
                <input v-model="state.input.firstName" type="text" class="mdc-text-field__input pt-1">
                <p v-for="(error, key) in state.validation.errors?.firstname" :key="key" class="mdc-theme--secondary text">
                  <strong>{{ error }}</strong>
                </p>
              </label>
          </div>
          <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
              <label class="mdc-text-field w-100">
                <strong>Last Name</strong>
                <input v-model="state.input.lastName" type="text" class="mdc-text-field__input pt-1">
                <p v-for="(error, key) in state.validation.errors?.lastname" :key="key" class="mdc-theme--secondary text">
                  <strong>{{ error }}</strong>
                </p>
              </label>
          </div>
          <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
              <label class="mdc-text-field w-100">
                <strong>Password</strong>
                <input v-model="state.input.password" type="password" class="mdc-text-field__input pt-1">
                <p v-for="(error, key) in state.validation.errors?.password" :key="key" class="mdc-theme--secondary text">
                  <strong>{{ error }}</strong>
                </p>
              </label>
          </div>
          <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
              <label class="mdc-text-field w-100">
                <strong>Confirm Password</strong>
                <input v-model="state.input.passwordConfirmation" type="password" class="mdc-text-field__input pt-1">
              </label>
          </div>
          <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
            <label class="mdc-text-field w-100">
              <strong>Select Type</strong>
              <select v-model="state.input.type" class="mdc-text-field__input pt-1">
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
              <strong>Select Status</strong>
              <select v-model="state.input.status" class="mdc-text-field__input pt-1">
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