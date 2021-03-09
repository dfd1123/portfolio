<template>
    <div class="ai_wrapper">
        <div class="ai_container text-center">
            <!--<span>{{ `${items.length}${this.__('test.message1', {'wow' : '와우'})}`}}</span>-->
            <div class="row justify-content-center">
                <div class="input-group col-md-8">
                    <input type="text" class="form-control" v-model="value">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button" @click="buttonhandler">
                            <span
                                v-if="isStoring || isUpdating"
                                class="spinner-border spinner-border-sm"
                            ></span>
                            <span v-else-if="editingItem !== null">OK</span>
                            <span v-else>+</span>
                        </button>
                    </span>
                </div>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Example Component</div>
                        <div
                            class="card-body d-flex align-items-center"
                            v-for="item in items"
                            :key="item.id"
                        >
                            <span class="mx-auto">{{item.value}}</span>
                            <button class="btn btn-success" type="button" @click="edit(item)">
                                <span>edit</span>
                            </button>
                            <button class="btn btn-danger" type="button" @click="destroy(item)">
                                <span
                                    v-if="isDestroying && destroyingItem === item.id"
                                    class="spinner-border spinner-border-sm"
                                ></span>
                                <span v-else>-</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <p class="mt-5 mb-3 text-muted">© 2019-2019</p>
            <a href="#" @click.prevent="logout">Logout</a>
        </div>
    </div>
</template>

<script>
export default {
    async beforeRouteEnter(to, from, next) {
        if (!localStorage.passportToken) {
            return next("/");
        }
        next();
    },
    data() {
        return {
            isStoring: false,
            isDestroying: false,
            isUpdating: false,
            destroyingItem: null,
            editingItem: null,
            value: "",
            items: []
        };
    },
    async created() {
        let token = localStorage.passportToken;
        axios.defaults.headers.common["Authorization"] = `Bearer ${token}`;

        this.index();
    },
    methods: {
        async index() {
            const response = await axios.get("/api/tests");
            this.items = response.data;
        },
        async store() {
            if (this.isStoring) {
                return;
            }
            this.isStoring = true;

            await axios.post("/api/tests", {
                value: this.value
            });
            await this.index();

            this.value = "";
            this.isStoring = false;
        },
        async destroy(item) {
            if (this.isDestroying) {
                return;
            }
            if(this.editingItem !== null) {
                return;
            }
            this.isDestroying = true;
            this.destroyingItem = item;

            await axios.delete(`/api/tests/${this.destroyingItem.id}`);
            const index = this.items.findIndex(
                item => item.id === this.destroyingItem.id
            );
            this.items.splice(index, 1);

            this.destroyingItem = null;
            this.isDestroying = false;
        },
        async update() {
            if (this.isUpdating) {
                return;
            }
            this.isUpdating = true;

            await axios.put(`/api/tests/${this.editingItem.id}`, {
                value: this.value
            });
            const response = await axios.get(
                `/api/tests/${this.editingItem.id}`
            );
            const index = this.items.findIndex(
                item => item.id === this.editingItem.id
            );
            const item = response.data;
            this.items.splice(index, 1, item);

            this.editingItem = null;
            this.value = "";
            this.isUpdating = false;
        },
        buttonhandler() {
            if (this.editingItem === null) {
                this.store();
            } else {
                this.update();
            }
        },
        edit(item) {
            this.editingItem = item;
            this.value = item.value;
        },
        async logout() {
            await axios.get("/api/logout");
            localStorage.removeItem("passportToken");
            axios.defaults.headers.common["Authorization"] = undefined;
            this.$swal({
                type: "success",
                text: "Logout success",
                allowOutsideClick: false
            });
            this.$router.replace({ path: "/" });
            this.$store.commit("reset");
        }
    }
};
</script>

<style scoped>
.ai_wrapper {
    position: relative;
    height: 100%;
    padding-top: 43px;
    margin-bottom: -53px;
    padding-bottom: 52px;
}

.ai_container {
    min-height: 100%;
    height: 100%;
    overflow-x: hidden;
    overflow-y: auto;
}
</style>
