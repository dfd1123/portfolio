import { createAction, handleActions } from 'redux-actions';

const SET_LANGUAGE = 'language/SET_LANGUAGE';


export const setLanguage = createAction(SET_LANGUAGE, c_lang => c_lang);

const initialState = {
    current: ""
};

const language = handleActions(
    {
        [SET_LANGUAGE]: (state, action) => (
            // console.log(state, action),
            {
                current: action.payload
            })
    },
    initialState
);

export default language;