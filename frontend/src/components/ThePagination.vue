<script setup lang="ts">

</script>

<template>
    <div class="mdc-layout-grid">
        <div class="mdc-layout-grid__inner">
            <div class="mdc-layout-grid__cell stretch-card mdc-layout-grid__cell--span-12">
                <div class="mdc-form-field pr-1">
                    Rows per page:
                </div>
                <div class="mdc-form-field pr-1">
                    <label class="mdc-text-field">
                        <select class="mdc-text-field__input pt-1">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </label>
                    <i class="mdc-drawer-arrow material-icons">keyboard_arrow_down</i>
                </div>
                <div class="mdc-form-field pr-1">
                    1–10 of 100
                </div>
                <div class="mdc-form-field pr-1">
                    <i class="mdc-drawer-arrow material-icons">chevron_left</i>
                </div>
                <div class="mdc-form-field pr-1">
                    <i class="mdc-drawer-arrow material-icons">chevron_right</i>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.stretch-card {
    justify-content: flex-end;
}
</style>