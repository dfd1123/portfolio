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
            default: '#1d324f'
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
    position: fixed;
    bottom: 0;
    z-index: 3;
}

#footer.active button {
    width: 100%;
    color: #fff;
    font-family: inherit;
    font-size: 17px;
    font-weight: bold;
    padding: 18px 0;
    border: none;
    line-height: 1;
}

#footer button {
    background: #dcdcdc;
    width: 100%;
    color: #b4b4b4;
    font-size: 17px;
    font-weight: bold;
    padding: 18px 0;
    border: none;
}

#footer button:focus {
    outline: none
}
</style>
