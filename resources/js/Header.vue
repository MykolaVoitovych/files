<template>
    <ul class="nav justify-content-end mb-5">
        <li class="nav-item">
            <a class="nav-link" href="#">
                <router-link
                    :to="{ name: 'search' }"
                >
                    Search
                </router-link></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">
                <router-link
                    :to="{ name: 'create' }"
                >
                    Create
                </router-link>
            </a>
        </li>
    </ul>
</template>

<script>
export default {
    methods: {
        logout () {
            axios.post(route('logout')).then(response => {
                if (response.data.success) {
                    document.location.href = response.data.redirect_url
                }
            }).catch(e => {
                console.error(e)
            })
        }
    }
}
</script>

<style scoped>

</style>
