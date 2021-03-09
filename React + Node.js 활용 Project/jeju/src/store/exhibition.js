import { createAction, handleActions } from 'redux-actions';

const SETID = 'exhibition/SETID';

export const setID = createAction(SETID, id => id);

const initialState = {
    current: -1
};

const exhibition = handleActions(
    {
        [SETID]: (state, action) => ({
            current: action.payload
        })
    },
    initialState
);

export default exhibition;