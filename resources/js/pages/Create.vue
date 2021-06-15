<template>
    <form>
        <h5 class="card-title">Create</h5>
        <b-form-group label="Tagged input using select" label-for="tags-component-select">
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
                        :disabled="disabled || options.length === 0"
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
        <b-button variant="primary @click.prevent="create"">Button</b-button>
    </form>
</template>

<script>
export default {
    data () {
        return {
            form: {
                tags: [],
                file: null
            },
            errors: [],
            options: ['Apple', 'Orange', 'Banana', 'Lime', 'Peach', 'Chocolate', 'Strawberry']
        }
    },
    methods: {
        create () {
            let url = route('files.store')
            axios.post(url, this.form).then(response => {
                this.$router.push({ name: 'search'})
            }).catch(error => {
                let errorStatus = error.response ? error.response.status : null
                if ((errorStatus == 422) && error.response && error.response.data) {
                    this.errors = error.response.data.errors
                }
                console.error(error)
            })
        }
    }
}
</script>

<style scoped>

</style>
