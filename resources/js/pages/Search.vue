<template>
    <div>
        <b-card class="text-center mb-2">
            <div class="row">
                <div class="col-12">
                    <b-button
                        v-for="(tag, key) in selectedTags"
                        :key="key"
                        class="float-left mr-1 mb-1"
                        @click.prevent="removeTag(tag)"
                    >{{ tag }} ×</b-button>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <b-form-textarea id="query" v-model="query" placeholder="Add search tags" readonly></b-form-textarea>
                </div>
            </div>
            <b-dropdown  variant="success" id="add" text="Add" class="mr-2 float-left">
                <b-dropdown-item
                    v-for="(option, key) in addOptions"
                    :key="key"
                    @click="addTag('+', option)"
                >
                    {{ option }}
                </b-dropdown-item>
            </b-dropdown>
            <b-dropdown  variant="danger" id="except" text="Except" class="mr-2 float-left">
                <b-dropdown-item
                    v-for="(option, key) in exceptOptions"
                    :key="key"
                    @click="addTag('-', option)"
                >
                    {{ option }}
                </b-dropdown-item>
            </b-dropdown>
            <b-button
                class="float-right"
                variant="primary"
                :disabled="!selectedTags.length"
                @click.prevent="search"
            >
                SEARCH
            </b-button>
        </b-card>
        <b-card>
            <h2>RESULTS</h2>
            <b-list-group>
                <b-list-group-item v-for="(file, key) in searchFiles" :key="key">{{ file}}</b-list-group-item>
            </b-list-group>
            <h2>TAGS</h2>
            <b-list-group>
                <b-list-group-item v-for="(tag, key) in searchTags"  :key="key">
                    {{ tag.name }}, file count: {{ tag.files_count }}
                </b-list-group-item>
            </b-list-group>
        </b-card>
    </div>
</template>

<script>
import {mapGetters} from 'vuex'

export default {
    data () {
        return {
            query: '',
            addTags: [],
            exceptTags: [],
            searchTags: [],
            searchFiles: [],
        }
    },
    computed: {
        ...mapGetters(['getTags']),
        tags () {
            return this.getTags.map(tag => tag.name)
        },
        addOptions () {
            return this.tags.filter(tag => !this.addTags.includes(tag))
        },
        exceptOptions () {
            return this.tags.filter(tag => !this.exceptTags.includes(tag))
        },
        selectedTags () {
            return [...this.addTags, ...this.exceptTags]
        },
    },
    methods: {
        addTag (sign, value) {
            this.removeTag(value)
            if (sign === '+') {
                this.addTags.push(value)
            } else {
                this.exceptTags.push(value)
            }
            this.query += sign + value
        },
        removeTag (tag) {
            var exceptIndex = this.exceptTags.indexOf(tag);
            var addIndex = this.addTags.indexOf(tag);
            if (exceptIndex !== -1) {
                this.exceptTags.splice(exceptIndex, 1);
                this.query = this.query.replace('-' + tag, '')
            } else if (addIndex !== -1) {
                this.addTags.splice(addIndex, 1);
                this.query = this.query.replace('+' + tag, '')
            }
        },
        search () {
            let url = route('search', this.query)
            axios.get(url).then(response => {
                this.searchFiles = response.data.files
                this.searchTags = response.data.tags
            }).catch(error => {
                console.error(error)
            })
        }
    }
}
</script>

<style scoped>

</style>
