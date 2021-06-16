<template>
    <form>
        <h5 class="card-title">Upload file</h5>
        <b-form-group label="Tags" label-for="tags-component-select">
            <!-- Prop `add-on-change` is needed to enable adding tags vie the `change` event -->
            <b-form-tags
                id="tags-component-select"
                v-model="form.tags"
                size="lg"
                class="mb-2"
                add-on-change
                no-outer-focus
            >
                <template v-slot="{ tags, inputAttrs, inputHandlers, disabled, removeTag }">
                    <ul v-if="tags.length > 0" class="list-inline d-inline-block mb-2">
                        <li v-for="tag in tags" :key="tag" class="list-inline-item">
                            <b-form-tag
                                @remove="removeTag(tag)"
                                :title="tag"
                                :disabled="disabled"
                                variant="info"
                            >{{ tag }}</b-form-tag>
                        </li>
                    </ul>
                    <b-form-select
                        v-bind="inputAttrs"
                        v-on="inputHandlers"
                        :disabled="disabled || getTags.length === 0"
                        :options="options"
                    >
                        <template #first>
                            <!-- This is required to prevent bugs with Safari -->
                            <option disabled value="">Choose a tag...</option>
                        </template>
                    </b-form-select>
                </template>
            </b-form-tags>
        </b-form-group>
        <b-form-group label="File" label-for="file">
            <b-form-file
                id="file"
                v-model="form.file"
                :state="Boolean(form.file)"
                placeholder="Choose a file or drop it here..."
                drop-placeholder="Drop file here..."
            ></b-form-file>
        </b-form-group>
        <b-button class="float-right" variant="primary" @click.prevent="create">ADD FILE</b-button>
    </form>
</template>

<script>
import {mapGetters} from 'vuex'

export default {
    data () {
        return {
            form: {
                tags: [],
                file: null
            },
            errors: [],
        }
    },
    computed: {
        ...mapGetters(['getTags']),
        options () {
            return this.getTags.map(tag => tag.name)
        }
    },
    methods: {
        create () {
            let url = route('file.store')
            let data = this.makeFormData()
            axios.post(url, data).then(response => {
                this.$router.push({ name: 'search'})
            }).catch(error => {
                let errorStatus = error.response ? error.response.status : null
                if ((errorStatus == 422) && error.response && error.response.data) {
                    this.errors = error.response.data.errors
                }
                console.error(error)
            })
        },
        makeFormData () {
            let data = new FormData()
            data.append('file', this.form.file)
            this.form.tags.forEach((tag, key) => {
                data.append(`tags[${key}]`, tag)
            })

            return data
        }
    }
}
</script>

<style scoped>

</style>
