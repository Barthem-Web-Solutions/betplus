<template>
    <div class="item-game text-gray-700 w-[132px] h-[153.6px] md:w-[166.33px] md:h-[180px] mr-4 cursor-pointer relative">
        <RouterLink :to="{ name: 'casinoPlayPage', params: { id: game.id, slug: game.game_code }}">
            <img :src="game.distribution === 'kagaming' ? game.cover : `/storage/${game.cover}`" alt="" class="h-full">
        </RouterLink>
        <div class="overlay absolute top-0 left-0 w-full h-full bg-black opacity-0 hover:opacity-50 flex items-center justify-center transition-opacity">
            <RouterLink :to="{ name: 'casinoPlayPage', params: { id: game.id, slug: game.game_code }}">
                <div class="w-[80%]">
                    <button class="text-white bg-[#36a979] px-4 py-2 rounded hover:bg-[#36a979] flex flex-row gap-2 items-center justify-center">
                        <svg height="1em" viewBox="0 0 384 512" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M73 39c-14.8-9.1-33.4-9.4-48.5-.9S0 62.6 0 80V432c0 17.4 9.4 33.4 24.5 41.9s33.7 8.1 48.5-.9L361 297c14.3-8.7 23-24.2 23-41s-8.7-32.2-23-41L73 39z" fill="currentColor"></path></svg>
                        JOGAR
                    </button>
                </div>
            </RouterLink>
        </div>
    </div>
</template>


<script>
import { RouterLink } from "vue-router";

export default {
    props: [ 'index', 'game'],
    components: { RouterLink },
    data() {
        return {
            isLoading: false,
            modalGame: null,
        }
    },
    setup(props) {


        return {};
    },
    computed: {

    },
    mounted() {



    },
    methods: {

    },
    created() {

    },
    watch: {

    },
};
</script>

<style scoped>

</style>
