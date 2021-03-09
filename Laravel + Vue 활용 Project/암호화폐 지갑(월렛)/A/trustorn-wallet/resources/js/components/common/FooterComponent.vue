<template>
    <footer id="footer" :class="{ active: active }">
        <button type="button" @click="buttonClicked" :style="{'background' : active ? activeColor : ''}">{{buttonText}}</button>
    </footer>
</template>

<script>
export default {
    props: {
        buttonText: {},
        active: {
            default: true
        },
        activeColor: {
            default: 'linear-gradient(to right, #64E196, #19B4AA)'
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
    width: 100%;
    height: 3.315rem;
    position: fixed;
    left:0;
    bottom: 0;
    z-index: 3;
}

#footer.active button {
    color: #fff;
    width: 100%;
    height: 100%;
    font-size: 1.15rem;
    font-weight: 400;
    border: none;
    transition: background 0.2s;
}

#footer button {
    background: #EBF0F0;
    color: #BEC8C8;
    width: 100%;
    height: 100%;
    border: none;
    font-size: 1.15rem;
    font-weight: 400;
    border-top-left-radius: 20px;
    border-top-right-radius: 20px;
}

#footer button:focus {
    outline: none
}
</style>
