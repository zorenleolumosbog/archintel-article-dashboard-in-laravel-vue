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
import { userAuth } from '@/stores/index';
const authStore = userAuth();
const router = useRouter();

const state = reactive({
  inputFilter: {
      search: '',
      dateFrom: '',
      dateTo: ''
  },
  formTitle: '',
  input: {
    companyId: '',
    title: '',
    link: '',
    date: '',
    content: '',
    status: 'For Edit',
    file: {
      src: '',
      icon: null
    }
  },
  selectedRecord: null,
  records: null,
  companies: null,
  pagination: {
      current: 1,
      limit: 10
  },
  validation: {
    showModal: false,
    saving: false,
    publishing: false,
    isLoadingRecords: false,
    isSuccess: false,
    errors: []
  }
});

onMounted(() => {
  getRecords();
  getCompanies();
});

const debouncedRecords = useDebounceFn(() => {
  getRecords();
}, 1000);

const generageLink = useDebounceFn(() => {
  if(state.input.title.length > 0) {
    state.input.link = window.location.href + "/" + state.input.title
                                              .toLowerCase()
                                              .trim()
                                              .replace(/[^\w\s-]/g, '')
                                              .replace(/[\s_-]+/g, '-')
                                              .replace(/^-+|-+$/g, '');
  } else {
    state.input.link = '';
  }

}, 1000);

const currentPage = (val: number) => {
    state.pagination.current = val;
    getRecords();
};

const getRecords = () => {
  state.validation.isLoadingRecords = true;

  axios.get(`${process.env.API_URL}/articles`, {
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

const getCompanies = () => {
  state.records = null;

  axios.get(`${process.env.API_URL}/companies`, {
    headers: {
        Authorization: `Bearer ${localStorage.getItem("accessToken")}`,
    }
  })
  .then((response) => {
    state.companies = response.data;
  });
};

const add = () => {
  state.formTitle = 'Add New Article';
  state.validation.showModal = true;
  state.validation.errors = [];
};

const edit = (id: string) => {
  state.formTitle = 'Edit Article';
  state.validation.showModal = true;
  state.validation.errors = [];

  state.selectedRecord = hooks.useFilterRecord('id', state.records?.data, id);
    
  state.input = {
    companyId: state.selectedRecord?.company.id,
    title: state.selectedRecord?.title,
    link: state.selectedRecord?.link,
    date: state.selectedRecord?.date,
    content: state.selectedRecord?.content,
    status: state.selectedRecord?.status,
    file: {
      src: state.selectedRecord?.image,
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
  formData.append('company_id', state.input.companyId);
  formData.append('image', state.input.file?.icon);
  formData.append('title', state.input.title);
  formData.append('link', state.input.link);
  formData.append('date', state.input.date);
  formData.append('content', state.input.content);
  formData.append('status', state.input.status);

  axios.post(`${process.env.API_URL}/articles`, formData, {
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

const update = (status = 'For Edit') => {
  if(status === 'Published') {
    state.validation.publishing = true;
  } else {
    state.validation.saving = true;
  }

  const formData = new FormData();
  formData.append('_method', 'PUT');
  formData.append('company_id', state.input.companyId);
  formData.append('title', state.input.title);
  formData.append('link', state.input.link);
  formData.append('date', state.input.date);
  formData.append('content', state.input.content);
  formData.append('status', status);
  if(state.input.file.icon) {
    formData.append('image', state.input.file.icon);
  }

  axios.post(`${process.env.API_URL}/articles/${state.selectedRecord?.id}`, formData, {
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
    if(status === 'Published') {
      state.validation.publishing = true;
    } else {
      state.validation.saving = true;
    }
    state.validation.errors = error.response.data.errors;
  });
};

const cancel = () => {
  clear();
};

const clear = () => {
  state.formTitle;
  state.input = {
    companyId: '',
    title: '',
    link: '',
    date: '',
    content: '',
    status: 'For Edit',
    file: {
      src: '',
      icon: null
    }
  };
  state.selectedRecord = null,
  state.validation = {
    showModal: false,
    saving: false,
    publishing: false,
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
              <h1 class="mdc-card__title mdc-card__title--large">Manage All Media</h1>
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
                  <th class="text-left">Image</th>
                  <th class="text-left">Title</th>
                  <th class="text-left">Link</th>
                  <th class="text-left">Writer</th>
                  <th class="text-left">Editor</th>
                  <th class="text-left">Status</th>
                  <th class="text-left">Date Created</th>
                </tr>
              </thead>
              <tbody>
                <template v-if="state.validation.isLoadingRecords">
                  <tr>
                    <td colspan="5" class="text-center"><h3>Loading...</h3></td>
                  </tr>
                </template>
                <template v-else>
                  <tr v-for="record in state.records?.data" :key="record">
                      <td class="text-left">
                        <template v-if="authStore.currentUser.type === 'Writer' && record?.status === 'For Edit'">
                          <div @click="edit(record?.id)" class="col mdc-button" data-mdc-auto-init="MDCRipple">
                            <span class="material-symbols-outlined">edit_document</span>
                          </div>
                        </template>
                        <template v-if="authStore.currentUser.type === 'Editor'">
                          <div @click="edit(record?.id)" class="col mdc-button" data-mdc-auto-init="MDCRipple">
                            <span class="material-symbols-outlined">edit_document</span>
                          </div>
                        </template>
                      </td>
                      <td class="text-left"><img :src="record?.image" width="30"/></td>
                      <td class="text-left">{{ record?.title }}</td>
                      <td class="text-left">{{ record?.link }}</td>
                      <td class="text-left">{{ record?.writer?.firstname }} {{ record?.writer?.lastname }}</td>
                      <td class="text-left">{{ record?.editor?.firstname }} {{ record?.editor?.lastname }}</td>
                      <td class="text-left">{{ record?.status }}</td>
                      <td class="text-left">{{ hooks.useToLocaleDateString(record?.created_at).value }}</td>
                  </tr>
                </template>
              </tbody>
            </table>
            <the-pagination :records="state.records" :pagination="state.pagination" @getRecords="getRecords" @currentPage="currentPage"></the-pagination>
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
          <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
            <label class="mdc-text-field w-100">
              <span>Select Company</span>
              <select v-model="state.input.companyId" class="mdc-text-field__input pt-1">
                <option disabled></option>
                <option v-for="company in state.companies?.data" :key="company" :value="company.id">{{ company.name }}</option>
              </select>
              <p v-for="(error, key) in state.validation.errors?.status" :key="key" class="mdc-theme--secondary text">
                <strong>{{ error }}</strong>
              </p>
            </label>
          </div>
          <div class="mdc-layout-grid__cell mdc-layout-grid__cell--span-12">
              <label class="mdc-text-field w-100">
                <span>Image</span>
                <hr>
                <img :src="state.input.file.src ? state.input.file.src : 'https://via.placeholder.com/100'" width="100"/>
                <input @change="handleFileIcon" type="file" ref="front" id="file" accept="image/png, image/gif, image/jpeg" class="mdc-text-field__input pt-1">
                <p v-for="(error, key) in state.validation.errors?.image" :key="key" class="mdc-theme--secondary text">
                  <strong>{{ error }}</strong>
                </p>
              </label>
          </div>
          <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
              <label class="mdc-text-field w-100">
                <span>Title</span>
                <input @keydown="generageLink" v-model="state.input.title" type="text" class="mdc-text-field__input pt-1">
                <p v-for="(error, key) in state.validation.errors?.title" :key="key" class="mdc-theme--secondary text">
                  <strong>{{ error }}</strong>
                </p>
              </label>
          </div>
          <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
              <label class="mdc-text-field w-100">
                <span>Link</span>
                <input v-model="state.input.link" type="text" class="mdc-text-field__input pt-1">
                <p v-for="(error, key) in state.validation.errors?.link" :key="key" class="mdc-theme--secondary text">
                  <strong>{{ error }}</strong>
                </p>
              </label>
          </div>
          <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
              <label class="mdc-text-field w-100">
                <span>Date</span>
                <input v-model="state.input.date" type="datetime-local" class="mdc-text-field__input pt-1">
                <p v-for="(error, key) in state.validation.errors?.date" :key="key" class="mdc-theme--secondary text">
                  <strong>{{ error }}</strong>
                </p>
              </label>
          </div>
          <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
              <label class="mdc-text-field w-100">
                <span>Content</span>
                <textarea v-model="state.input.content" rows="10" class="mdc-text-field__input pt-1"></textarea>
                <p v-for="(error, key) in state.validation.errors?.content" :key="key" class="mdc-theme--secondary text">
                  <strong>{{ error }}</strong>
                </p>
              </label>
          </div>
      </div>
    </the-form-modal>
</template>

<style scoped>
hr {
  border-width: 0px;
}
</style>