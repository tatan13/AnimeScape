<template>
    <div v-if="isLikeCast">
        <a href="#" @click="dislike(castId)">お気に入りを解除する</a>
    </div>
    <div v-else>
        <a href="#" @click="like(castId)">お気に入り声優として登録する</a>
    </div>
</template>

<script>
    export default {
        props:{
            propsCastId: {
                type: Number,
                required: true,
            },
            defaultIsLikeCast: {
                type: Boolean,
                required: true,
            },
        },
        data() {
            return {
                castId: 0,
                isLikeCast: false,
            };
        },
        created() {
            this.castId = this.propsCastId
            this.isLikeCast = this.defaultIsLikeCast
        },
        methods: {
            like(castId) {
                let url = `/cast/${castId}/like`
                axios.get(url)
                .then(response => {
                    this.isLikeCast = true
                })
                .catch(error =>{
                    alert(error)
                });
            },
            dislike(castId) {
                let url = `/cast/${castId}/dislike`
                axios.get(url)
                .then(response => {
                    this.isLikeCast = false
                })
                .catch(error =>{
                    alert(error)
                });
            },
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
