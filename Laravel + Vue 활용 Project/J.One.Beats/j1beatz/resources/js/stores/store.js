import Vuex from "vuex";

function initialState() {
    return {
        isTest: false,
        isGlobalLoading: false,
        isPlaybarVisible: false,
        isPlaylistVisible: false,
        isPlaylistOpened: false,
        isPlaylistEditing: false,
        isPlaylistPlaying: false,
        isNoticeToPurchaseVisible: false,
        isMyAlbumPopupVisible: false,
        beatAddToAlbum: {},
        user: {
            license: {}
        },
        producer: {},
        moods: [],
        selectedMoods: [],
        genres: [],
        currentPlayingBeat: {},
        selectedBeats: [],
        deletedBeats: [],
        playlist: [],
        editingPlaylist: [],
        cartlist: [],
        followlist: [],
        playfolder: {}
    };
}

export default new Vuex.Store({
    state: initialState(),
    getters: {
        isSuspendedUser: state => {
            return state.user.user_id && state.user.state === 0 ? true : false;
        },
        isUser: state => {
            return state.user.user_id && state.user.state === 1 ? true : false;
        },
        isLeavingUser: state => {
            return state.user.user_id && state.user.state === 2 ? true : false;
        },
        isWaitingProducer: state => {
            return state.producer.prdc_id && state.producer.state === 0
                ? true
                : false;
        },
        isRejectedProducer: state => {
            return state.producer.prdc_id &&
                state.producer.state === 0 &&
                state.producer.prdc_reject !== null
                ? true
                : false;
        },
        isProducer: state => {
            return state.producer.prdc_id && state.producer.state === 1
                ? true
                : false;
        },
        isSuspendedProducer: state => {
            return state.producer.prdc_id && state.producer.state === 2
                ? true
                : false;
        }
    },
    mutations: {
        updateIsTest(state, payload) {
            state.isTest = payload;
        },
        updateIsGlobalLoading(state, payload) {
            state.isGlobalLoading = payload;
        },
        updateIsPlaybarVisible(state, payload) {
            state.isPlaybarVisible = payload;
        },
        updateIsPlaylistVisible(state, payload) {
            state.isPlaylistVisible = payload;
        },
        updateIsPlaylistOpened(state, payload) {
            state.isPlaylistOpened = payload;
        },
        updateIsPlaylistEditing(state, payload) {
            state.isPlaylistEditing = payload;
        },
        updateIsPlaylistPlaying(state, payload) {
            state.isPlaylistPlaying = payload;
        },
        updateIsNoticeToPurchaseVisible(state, payload) {
            state.isNoticeToPurchaseVisible = payload;
        },
        updateUser(state, payload) {
            state.user = payload;
        },
        mergeUser(state, payload) {
            state.user = { ...state.user, ...payload };
        },
        updateProducer(state, payload) {
            state.producer = payload;
        },
        mergeProducer(state, payload) {
            state.producer = { ...state.producer, ...payload };
        },
        updateMoods(state, payload) {
            state.moods = payload;
        },
        updateSelectedMoods(state, payload) {
            state.selectedMoods = payload;
        },
        updateGenres(state, payload) {
            state.genres = payload;
        },
        updateSelectedBeats(state, payload) {
            state.selectedBeats = payload;
        },
        updateDeletedBeats(state, payload) {
            state.deletedBeats = payload;
        },
        updateCurrentPlayingBeat(state, payload) {
            state.currentPlayingBeat = payload;
        },
        updatePlaylist(state, payload) {
            state.playlist = payload;
        },
        updateEditingPlaylist(state, payload) {
            state.editingPlaylist = payload;
        },
        updateCartlist(state, payload) {
            state.cartlist = payload;
        },
        updateFollowlist(state, payload) {
            state.followlist = payload;
        },
        updatePlayfolder(state, payload) {
            state.playfolder = payload;
        },
        updateBeatAddToAlbum(state, payload){
            state.beatAddToAlbum = payload;
        },
        updateIsMyAlbumPopupVisible(state, payload){
            state.isMyAlbumPopupVisible = payload;
        },
    }
});
