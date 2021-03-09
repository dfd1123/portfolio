<template>
    <div class="tab_container">
        <div class="tap">
            <ul>
                <li v-for="tab in tabs" :key="tab._uid" :class="{ 'active': tab.isActive }">
                    <a @click="selectTab(tab)">{{ tab.name }}</a>
                </li>
            </ul>
        </div>

        <div class="container_tap">
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

.tap {
    width: 100%;
    background: white;
    border-bottom: 1px solid #f1f1f1;
    height: auto;
    position: absolute;
    top: 0;
    z-index: 1;
    white-space: nowrap;
    overflow-x: scroll;
}

.tap ul {
    text-align: center;
    color: #5a5a5a;
}

ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

ul li {
    list-style: none;
}

.tap ul li a {
    color: #5a5a5a;
    font-weight: 300;
}

.tap ul li.active a {
    color: #0072ff;
    font-weight: bold;
}

.tap ul li {
    display: inline-block;
    font-size: 13px;
    padding: 17px 9px;
    letter-spacing: -1px;
    cursor: pointer;
}

.container_tap {
    width: 100%;
    height: 100%;
    position: absolute;
    padding-top: 48px;
    background: #fafafa;
}

a:hover {
    color: #0056b3;
    text-decoration: none;
}
</style>