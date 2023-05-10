<script setup lang="ts">
const props = defineProps([
    'records',
    'pagination'
]);

const emit = defineEmits([
    'currentPage'
]);

const getPageNumberFromURLParam = (val: any) => {
    if(val) {
        var url = new URL(val);
        var pageNumber = url.searchParams.get("page");
        
        emit('currentPage', pageNumber);
    }
    
    return false;
}
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
                        <select @change="$emit('getRecords')" v-model="props.pagination.limit" class="mdc-text-field__input pt-1">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </label>
                    <i class="mdc-drawer-arrow material-icons">keyboard_arrow_down</i>
                </div>
                <div class="mdc-form-field pr-1">
                    {{ `${records?.meta.current_page + "-" + records?.meta.last_page }` }} of {{ records?.meta.total }}
                </div>
                <div class="mdc-form-field pr-1">
                    <a v-if="records?.links.prev" href="javascript:void(0)" @click="getPageNumberFromURLParam(records?.links.prev)">
                        <i class="mdc-drawer-arrow material-icons">chevron_left</i>
                    </a>
                    <i v-else class="mdc-drawer-arrow material-icons">chevron_left</i>
                </div>
                <div class="mdc-form-field pr-1">
                    <a v-if="records?.links.next" href="javascript:void(0)" @click="getPageNumberFromURLParam(records?.links.next)">
                        <i class="mdc-drawer-arrow material-icons">chevron_right</i>
                    </a>
                    <i v-else class="mdc-drawer-arrow material-icons">chevron_right</i>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.stretch-card {
    justify-content: flex-end;
}
a {
    display: inline-flex;
    align-items: center;
    vertical-align: middle;
    text-decoration: none;
}
</style>