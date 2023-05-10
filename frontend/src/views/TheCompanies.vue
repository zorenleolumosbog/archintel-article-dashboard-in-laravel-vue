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
    name: '',
    status: '',
    file: {
      src: '',
      icon: null
    }
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
  state.records = null;

  axios.get(`${process.env.API_URL}/companies`, {
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
  })
  .catch((error) => {
    router.push({name: 'dashboard'});
  });
};

const add = () => {
  state.formTitle = 'Add New Company';
  state.validation.showModal = true;
  state.validation.errors = [];
};

const edit = (id: string) => {
  state.formTitle = 'Edit Company';
  state.validation.showModal = true;
  state.validation.errors = [];

  state.selectedRecord = hooks.useFilterRecord('id', state.records?.data, id);
    
  state.input = {
    name: state.selectedRecord?.name,
    status: state.selectedRecord?.status,
    file: {
      src: state.selectedRecord?.logo,
      icon: null
    }
  }
};

const handleFileIcon = (event: any) => {
    const file = event.target.files[0];
    state.input.file.src = URL.createObjectURL(file);
    state.input.file.icon = file;
}

const store = () => {
  state.validation.saving = true;

  const formData = new FormData();
  formData.append('logo', state.input.file.icon);
  formData.append('name', state.input.name);
  formData.append('status', state.input.status);

  axios.post(`${process.env.API_URL}/companies`, formData, {
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

  const formData = new FormData();
  formData.append('_method', 'PUT');
  formData.append('name', state.input.name);
  formData.append('status', state.input.status);
  if(state.input.file.icon) {
    formData.append('logo', state.input.file.icon);
  }

  axios.post(`${process.env.API_URL}/companies/${state.selectedRecord?.id}`, formData, {
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
    name: '',
    status: '',
    file: {
      src: '',
      icon: null
    }
  };
  state.selectedRecord = null,
  state.validation = {
    showModal: false,
    saving: false,
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
              <h1 class="mdc-card__title mdc-card__title--large">Manage Companies</h1>
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
                  <th class="text-left">Logo</th>
                  <th class="text-left">Name</th>
                  <th class="text-left">Status</th>
                  <th class="text-left">Date Created</th>
                </tr>
              </thead>
              <tbody>
                <template v-if="state.records">
                  <tr v-for="record in state.records?.data" :key="record">
                      <td class="text-left">
                        <div @click="edit(record?.id)" class="col mdc-button" data-mdc-auto-init="MDCRipple">
                          <span class="material-symbols-outlined">edit_document</span>
                        </div>
                      </td>
                      <td class="text-left"><img :src="record?.logo" width="30"/></td>
                      <td class="text-left">{{ record?.name }}</td>
                      <td class="text-left">{{ record?.status }}</td>
                      <td class="text-left">{{ hooks.useToLocaleDateString(record?.created_at).value }}</td>
                  </tr>
                </template>
                <template v-else>
                  <tr>
                    <td colspan="3" class="text-center"><h3>Loading...</h3></td>
                  </tr>
                </template>
              </tbody>
            </table>
            <the-pagination v-if="state.records" :records="state.records" :pagination="state.pagination" @getRecords="getRecords" @currentPage="currentPage"></the-pagination>
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
                <span>Logo</span>
                <input @change="handleFileIcon" type="file" ref="front" id="file" accept="image/png, image/gif, image/jpeg" class="mdc-text-field__input pt-1">
                <p v-for="(error, key) in state.validation.errors?.logo" :key="key" class="mdc-theme--secondary text">
                  <strong>{{ error }}</strong>
                </p>
              </label>
          </div>
          <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
              <label class="mdc-text-field w-100">
                <span>Name</span>
                <input v-model="state.input.name" type="text" class="mdc-text-field__input pt-1">
                <p v-for="(error, key) in state.validation.errors?.name" :key="key" class="mdc-theme--secondary text">
                  <strong>{{ error }}</strong>
                </p>
              </label>
          </div>
          <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
            <label class="mdc-text-field w-100">
              <span>Select Status</span>
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