<template>
    <div class="tab_container">
        <div class="tab">
            <ul>
                <li v-for="tab in tabs" :key="tab._uid" :class="{ 'active': tab.isActive }">
                    <a @click="selectTab(tab)">{{ tab.name }}</a>
                </li>
            </ul>
        </div>

        <div class="container_tab">
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return { tabs: [] };
    },
    created() {
        this.tabs = this.$children;
    },
    methods: {
        selectTab(selectedTab) {
            this.tabs.forEach((tab, index) => {
                tab.isActive = tab.name == selectedTab.name;
                if(tab.isActive) {
                    this.$emit('tabIndexSelected', index);
                }
            });
        }
    }
};
</script>

<style scoped>
.tab_container {
    height: 100%;
    width: 100%;
}

.tab {
    width: 100%;
    background: white;
    height: 45px;
    position: absolute;
    top: 0;
    z-index: 1;
    white-space: nowrap;
    overflow-x: scroll;
    background: linear-gradient(to right, rgb(100, 225, 150), rgb(25, 180, 170));
    line-height: 45px;
}

.tab ul {
    text-align: center;
    height: 100%;
    padding: 0;
    margin: 0;
}

.tab ul li a {
    color: white;
    font-weight: 300;
    font-size: 0.85em;
    padding: 0 12px;
}

.tab ul li.active a {
    color: white;
    font-weight: bold;
}

.tab ul li {
    display: inline-block;
    cursor: pointer;
    height: 100%;
}

.container_tab {
    width: 100%;
    height: 100%;
    position: absolute;
    padding-top: 45px;
}
</style>
