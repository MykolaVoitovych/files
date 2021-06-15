<template>
    <main>
        <Header />

        <div class="container">
            <div class="c-app-layout__inner">
                <router-view />
            </div>
        </div>
    </main>
</template>

<script>
import Header from "./Header";
export default {
    components: {
        Header
    }
}
</script>

<style scoped>

</style>
