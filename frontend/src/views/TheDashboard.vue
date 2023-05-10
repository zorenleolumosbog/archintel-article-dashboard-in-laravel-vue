<script setup lang="ts">
import ThePagination from '@/components/ThePagination.vue';
import axios from 'axios';
import { onMounted, reactive } from "vue";

const state = reactive({
  forEditRecords: null,
  forPublishedRecords: null,
  forEditPagination: {
      current: 1,
      limit: 10
  },
  forPublishedPagination: {
      current: 1,
      limit: 10
  },
  validation: {
    isLoadingRecords: false
  }
});

onMounted(() => {
  getForEditRecords();
  getForPublishedRecords();
});

const currentPageForEdit = (val: number) => {
    state.forEditPagination.current = val;
    getForEditRecords();
};

const currentPageForPubished = (val: number) => {
    state.forPublishedPagination.current = val;
    getForPublishedRecords();
};

const getForEditRecords = () => {
  state.validation.isLoadingRecords = true;

  axios.get(`${process.env.API_URL}/articles`, {
    headers: {
        Authorization: `Bearer ${localStorage.getItem("accessToken")}`,
    },
    params: {
      page: state.forEditPagination.current,
      limit: state.forEditPagination.limit,
      status: 'For Edit'
    }
  })
  .then((response) => {
    state.forEditRecords = response.data;
    state.validation.isLoadingRecords = false;
  });
};

const getForPublishedRecords = () => {
  state.validation.isLoadingRecords = true;

  axios.get(`${process.env.API_URL}/articles`, {
    headers: {
        Authorization: `Bearer ${localStorage.getItem("accessToken")}`,
    },
    params: {
      page: state.forPublishedPagination.current,
      limit: state.forPublishedPagination.limit,
      status: 'Published'
    }
  })
  .then((response) => {
    state.forPublishedRecords = response.data;
    state.validation.isLoadingRecords = false;
  });
};
</script>

<template>
    <div class="mdc-layout-grid">
      <div class="mdc-layout-grid__inner">
        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
          <div class="mdc-card table-responsive">
            <div class="table-heading px-2 px-1 border-bottom">
              <h1 class="mdc-card__title mdc-card__title--large">For Articles</h1>
            </div>
            <table class="table">
              <thead>
                <tr>
                  <th class="text-left">Image</th>
                  <th class="text-left">Title</th>
                  <th class="text-left">Link</th>
                  <th class="text-left">Writer</th>
                  <th class="text-left">Editor</th>
                </tr>
              </thead>
              <tbody>
                <template v-if="state.validation.isLoadingRecords">
                  <tr>
                    <td colspan="3" class="text-center"><h3>Loading...</h3></td>
                  </tr>
                </template>
                <template v-else>
                  <tr v-for="record in state.forEditRecords?.data" :key="record">
                      <td class="text-left"><img :src="record?.image" width="30"/></td>
                      <td class="text-left">{{ record?.title }}</td>
                      <td class="text-left">{{ record?.link }}</td>
                      <td class="text-left">{{ record?.writer?.firstname }} {{ record?.writer?.lastname }}</td>
                      <td class="text-left">{{ record?.editor?.firstname }} {{ record?.editor?.lastname }}</td>
                  </tr>
                </template>
              </tbody>
            </table>
            <the-pagination v-if="state.forEditRecords" :records="state.forEditRecords" :pagination="state.forEditPagination" @getRecords="getForEditRecords" @currentPage="currentPageForEdit"></the-pagination>
          </div>
        </div>
      </div>
    </div>

    <div class="mdc-layout-grid">
      <div class="mdc-layout-grid__inner">
        <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
          <div class="mdc-card table-responsive">
            <div class="table-heading px-2 px-1 border-bottom">
              <h1 class="mdc-card__title mdc-card__title--large">Published Articles</h1>
            </div>
            <table class="table">
              <thead>
                <tr>
                  <th class="text-left">Image</th>
                  <th class="text-left">Title</th>
                  <th class="text-left">Link</th>
                  <th class="text-left">Writer</th>
                  <th class="text-left">Editor</th>
                </tr>
              </thead>
              <tbody>
                <template v-if="state.validation.isLoadingRecords">
                  <tr>
                    <td colspan="3" class="text-center"><h3>Loading...</h3></td>
                  </tr>
                </template>
                <template v-else>
                  <tr v-for="record in state.forPublishedRecords?.data" :key="record">
                      <td class="text-left"><img :src="record?.image" width="30"/></td>
                      <td class="text-left">{{ record?.title }}</td>
                      <td class="text-left">{{ record?.link }}</td>
                      <td class="text-left">{{ record?.writer?.firstname }} {{ record?.writer?.lastname }}</td>
                      <td class="text-left">{{ record?.editor?.firstname }} {{ record?.editor?.lastname }}</td>
                  </tr>
                </template>
              </tbody>
            </table>
            <the-pagination v-if="state.forPublishedRecords" :records="state.forPublishedRecords" :pagination="state.forPublishedPagination" @getRecords="getForPublishedRecords" @currentPage="currentPageForPubished"></the-pagination>
          </div>
        </div>
      </div>
    </div>
</template>