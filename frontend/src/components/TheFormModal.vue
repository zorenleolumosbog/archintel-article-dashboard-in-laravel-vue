<script setup lang="ts">
import { userAuth } from '@/stores/index';
const authStore = userAuth();

const props = defineProps([
  'formTitle',
  'publishable',
  'selectedRecord',
  'validation'
]);
</script>

<template>
    <teleport to="body">
        <div class="modal">
            <div class="modal-content">
                <span @click="$emit('cancel')" class="close">&times;</span>
                <h2>{{ props.formTitle }}</h2>
                <form>
                    <div class="mdc-layout-grid">
            		    <slot></slot>
                    </div>
                    <div class="modal-footer">
                        <button @click="$emit('cancel')" type="button" class="mdc-button mdc-button--raised secondary-filled-button">
                            Cancel
                        </button>
                        <button v-if="selectedRecord" :disabled="props.validation.saving" @click="$emit('update')" type="button" class="mdc-button mdc-button--raised">
                            <template v-if="props.validation.saving">Saving...</template>
                            <template v-else>Save</template>
                        </button>
                        <button v-else :disabled="props.validation.saving" @click="$emit('store')" type="button" class="mdc-button mdc-button--raised">
                            <template v-if="props.validation.saving">Saving...</template>
                            <template v-else>Save</template>
                        </button>
                        <template v-if="publishable">
                          <button v-show="authStore.currentUser?.type === 'Editor' && !selectedRecord"
                          :disabled="props.validation.publishing" @click="$emit('store', 'Published')" type="button" class="mdc-button mdc-button--raised">
                              <template v-if="props.validation.publishing">Publishing...</template>
                              <template v-else>Publish</template>
                          </button>
                          <button v-show="authStore.currentUser?.type === 'Editor' && selectedRecord?.status === 'For Edit'"
                          :disabled="props.validation.publishing" @click="$emit('update', 'Published')" type="button" class="mdc-button mdc-button--raised">
                              <template v-if="props.validation.publishing">Publishing...</template>
                              <template v-else>Publish</template>
                          </button>
                        </template>
                    </div>
                </form>
            </div>
        </div>
    </teleport>
</template>

<style scoped>
.modal {
  position: fixed; /* Stay in place */
  z-index: 9; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content/Box */
.modal-content {
  background-color: #fefefe;
  margin: 6% auto; /* 15% from the top and centered */
  padding: 20px;
  border: 1px solid #888;
  width: 60%; /* Could be more or less, depending on screen size */
  position: relative; /* Make the close button relative to the modal */
}

/* Close Button */
.close {
  position: absolute;
  top: 0;
  right: 12px;
  color: #aaa;
  font-size: 28px;
  font-weight: bold;
  cursor: pointer;
}

/* Close Button Hover */
.close:hover {
  color: black;
}

/* Footer */
.modal-footer {
  display: flex;
  justify-content: flex-end;
  margin-top: 20px;
}

/* Cancel Button */
#cancel-btn {
  margin-right: 10px;
}

/* Save Button */
#save-btn {
  background-color: green;
  color: white;
  border: none;
  padding: 10px;
  border-radius: 5px;
  cursor: pointer;
}

/* Save Button Hover */
#save-btn:hover {
  background-color: darkgreen;
}
.mdc-button {
    margin-left: 10px;
}
.modal-content-overlay {
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 999; /* Sit on top */
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  color: #fff;
  background-color: rgba(0, 0, 0, 0.5); /* Black color with 50% opacity */
}
</style>