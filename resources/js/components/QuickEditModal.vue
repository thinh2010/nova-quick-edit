<template>
    <modal
        tabindex="-1"
        role="dialog"
        @modal-close="handleClose"
        :classWhitelist="[
          'flatpickr-current-month',
          'flatpickr-next-month',
          'flatpickr-prev-month',
          'flatpickr-weekday',
          'flatpickr-weekdays',
          'flatpickr-calendar',
        ]"
    >
        <form autocomplete="off"
              class="bg-white rounded-lg shadow-lg overflow-hidden w-action-fields">
            <h2 class="border-b border-40 py-8 px-8 text-90 font-normal text-xl">
                Quick Edit
            </h2>
            <div class="action">
                <div class="flex border-b border-40">
                    <div class="w-1/5 px-8 py-6">
                        <label for="guideline" class="inline-block text-80 pt-2 leading-tight">
                            Guideline <span class="text-danger text-sm">*</span>
                        </label>
                    </div>
                    <div class="py-6 px-8 w-4/5">
                        <trix-editor
                            id="guideline"
                            ref="theEditor"
                            @keydown.stop
                            @trix-initialize="initialize"
                            @trix-attachment-add="handleFileAdd"
                            @trix-attachment-remove="handleFileRemove"
                            @trix-file-accept="handleFileAccept"
                            @trix-change="handleChange"
                            :value="guideline"
                            placeholder="Guideline"
                            class="trix-content"
                        />
                    </div>
                </div>

                <div class="flex border-b border-40">
                    <div class="w-1/5 px-8 py-6">
                        <label for="sub-keywords" class="inline-block text-80 pt-2 leading-tight">
                            Sub Keywords
                        </label>
                    </div>
                    <div class="py-6 px-8 w-4/5">
                        <textarea v-model="subKeywords" id="sub-keywords" rows="5" placeholder="Sub Keywords" class="w-full form-control form-input form-input-bordered py-3 h-auto" spellcheck="false"></textarea>
                    </div>
                </div>


                <!--                <div v-for="childField in field.fields">-->
                <!--                    <component-->
                <!--                        :is="'form-' + childField.component"-->
                <!--                        :errors="errors"-->
                <!--                        :resource-name="resourceName"-->
                <!--                        :field="childField"-->
                <!--                        :ref="'field-' + childField.attribute"-->
                <!--                    />-->
                <!--                </div>-->
            </div>
            <div class="bg-30 px-6 py-3 flex">
                <div class="flex items-center ml-auto">
                    <button @click.prevent="update"
                            class="bg-primary hover:bg-primary-dark text-white font-bold py-2 px-4 rounded-full ml-1">
                        Update
                    </button>
                </div>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: "QuickEditModal",

    props: ['field', 'resourceName'],

    data() {
        return {
            draftId: uuidv4(),
            guideline: this.field.guideline,
            subKeywords: this.field.subKeywords,
            withFiles: true
        }
    },

    methods: {
        update() {
            Nova.request().post(`/quick-edit/update/${this.resourceName}`, {
                id: this.field.id,
                guideline: this.guideline,
                subKeywords: this.subKeywords,
            }).then(response => {
                this.$toasted.show('Successfully updated!', {type: 'success'})
            }).catch(error => {
                if (error.response.status === 422) {
                    this.$toasted.show(error.response.data.message, {type: 'error'});
                }
            })
        },

        handleClose() {
            this.$emit('close');
            this.cleanUp();
        },

        initialize() {
            this.$refs.theEditor.editor.insertHTML(this.guideline)

            if (this.disabled) {
                this.$refs.theEditor.setAttribute('contenteditable', false)
            }
        },

        handleChange() {
            // Event trigger: @trix-change="handleChange"
            this.guideline = this.$refs.theEditor.value;
        },

        handleFileAccept(e) {
            if (!this.withFiles) {
                e.preventDefault()
            }
        },

        handleFileAdd({attachment}) {
            if (attachment.file) {
                this.uploadAttachment(attachment)
            }
        },

        /**
         * Remove an attachment from the server
         */
        handleFileRemove({attachment: {attachment}}) {
            Nova.request()
                .delete(`/nova-api/articles/trix-attachment/guideline`, {
                    params: {attachmentUrl: attachment.attributes.values.url},
                })
                .then(response => {
                })
                .catch(error => {
                })
        },

        /**
         * Purge pending attachments for the draft
         */
        cleanUp() {
            if (this.withFiles) {
                Nova.request()
                    .delete(
                        `/nova-api/articles/trix-attachment/guideline/${
                            this.draftId
                        }`
                    )
                    .then(response => console.log(response))
                    .catch(error => {
                    })
            }
        },

        /**
         * Upload an attachment
         */
        uploadAttachment(attachment) {
            const data = new FormData()
            data.append('Content-Type', attachment.file.type)
            data.append('attachment', attachment.file)
            data.append('draftId', this.draftId)

            Nova.request()
                .post(
                    `/nova-api/articles/trix-attachment/guideline`,
                    data,
                    {
                        onUploadProgress: function (progressEvent) {
                            attachment.setUploadProgress(
                                Math.round((progressEvent.loaded * 100) / progressEvent.total)
                            )
                        }
                    }
                )
                .then(({data: {url}}) => {
                    return attachment.setAttributes({
                        url: url,
                        href: url
                    })
                })
        },
    },
}

function uuidv4() {
    return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
        (
            c ^
            (crypto.getRandomValues(new Uint8Array(1))[0] & (15 >> (c / 4)))
        ).toString(16)
    )
}
</script>

<style scoped>

</style>
