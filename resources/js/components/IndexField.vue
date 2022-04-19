<template>
    <div>
        <span
            @click="showQuickEditModal"
            class="inline-flex cursor-pointer text-70 hover:text-primary mr-3"
        >
            <icon type="edit"/>
        </span>
        <quick-edit-modal
            v-if="quickEditModalOpen"
            :field="field"
            :resourceName="resourceName"
            @close="closeQuickEditModal"/>
    </div>
</template>

<script>
import QuickEditModal from "./QuickEditModal";

export default {
    props: ['resourceName', 'field'],

    data() {
        return {
            quickEditModalOpen: false
        }
    },

    methods: {
        showQuickEditModal() {
            this.quickEditModalOpen = true;
        },

        closeQuickEditModal() {
            this.quickEditModalOpen = false;
        }
    },

    components: {
        QuickEditModal
    }
}
</script>
