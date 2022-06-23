<template>
    <div v-if="isLikeCreater">
        <a href="#" @click="unlike(createrId)">お気に入りを解除する</a>
    </div>
    <div v-else>
        <a href="#" @click="like(createrId)">お気に入りクリエイターとして登録する</a>
    </div>
</template>

<script>
    export default {
        props:{
            propsCreaterId: {
                type: Number,
                required: true,
            },
            defaultIsLikeCreater: {
                type: Boolean,
                required: true,
            },
        },
        data() {
            return {
                createrId: 0,
                isLikeCreater: false,
            };
        },
        created() {
            this.createrId = this.propsCreaterId
            this.isLikeCreater = this.defaultIsLikeCreater
        },
        methods: {
            like(createrId) {
                let url = `/creater/${createrId}/like`
                axios.get(url)
                .then(response => {
                    this.isLikeCreater = true
                })
                .catch(error =>{
                    alert(error)
                });
            },
            unlike(createrId) {
                let url = `/creater/${createrId}/unlike`
                axios.get(url)
                .then(response => {
                    this.isLikeCreater = false
                })
                .catch(error =>{
                    alert(error)
                });
            },
        },
    }
</script>