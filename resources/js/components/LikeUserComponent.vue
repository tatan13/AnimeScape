<template>
    <div v-if="isLikedUser">
        <a href="#" @click="unlike(user_name)">お気に入りユーザーを解除する</a>
    </div>
    <div v-else>
        <a href="#" @click="like(user_name)">お気に入りユーザーとして登録する</a>
    </div>
</template>

<script>
    export default {
        props:{
            propsName: {
                type: String,
                required: true,
            },
            defaultLikedUserCount: {
                type: Number,
                required: true,
            },
            defaultIsLikeUser: {
                type: Boolean,
                required: true,
            },
        },
        data() {
            return {
                user_name: '',
                likedUserCount: 0,
                isLikedUser: false,
            };
        },
        created() {
            this.user_name = this.propsName
            this.likedUserCount = this.defaultLikedUserCount
            this.isLikedUser = this.defaultIsLikeUser
        },
        methods: {
            like(user_name) {
                let url = `/user_information/${user_name}/like`
                axios.get(url)
                .then(response => {
                    this.likedUserCount = response.data.likedUserCount
                    this.isLikedUser = true
                })
                .catch(error =>{
                    alert(error)
                });
                this.$emit('like-event', this.likedUserCount);
            },
            unlike(user_name) {
                let url = `/user_information/${user_name}/unlike`
                axios.get(url)
                .then(response => {
                    this.likedUserCount = response.data.likedUserCount
                    this.isLikedUser = false
                })
                .catch(error =>{
                    alert(error)
                });
                this.$emit('unlike-event', this.likedUserCount);
            },
        },
    }
</script>
