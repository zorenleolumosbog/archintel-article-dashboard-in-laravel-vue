<script setup lang="ts">
    const props = defineProps([
        'formTitle'
    ]);
</script>

<template>
    <teleport to="body">
        <div class="modal">
            <div class="modal-content">
                <span @click="$emit('cancel')" class="close">&times;</span>
                <h3>{{ formTitle }}</h3>
            </div>
        </div>
    </teleport>
</template>

<style scoped>
.modal {
  display: flex;
  justify-content: center;
  align-items: center;
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
  width: 30%; /* Could be more or less, depending on screen size */
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
h3 {
    text-align: center;
    margin-top: 24px;
}
</style>