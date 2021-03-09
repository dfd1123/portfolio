<template>
    <footer id="footer" v-bind:class="{ active: active }">
        <button type="button" @click="buttonClicked">{{buttonText}}</button>
    </footer>
</template>

<script>
export default {
    props: {
        buttonText: {},
        active: {
            default: true
        }
    },
    methods: {
        buttonClicked() {
            this.$emit("buttonClick");
        }
    }
};
</script>

<style scoped>
#footer {
    max-width: 1024px;
    padding: 30px 20px;
    width: 100%;
}

#footer.active button {
    background-color: #fff;
    color:#000;
}

#footer button {
    border-radius: 10px;
    -webkit-border-radius: 10px;
    border: 0;
    outline: none;
    background-color: #cccccc;
    cursor: pointer;
    font-size: inherit;
    font-weight: 700;
    color: #999;
    width: 100%;
    height: 40px;
}

#footer button:focus {
    outline: none
}

#footer button:active{
    background-color: #f2f2f2;
}
</style>
