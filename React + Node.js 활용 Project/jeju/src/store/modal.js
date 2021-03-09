import { createAction, handleActions } from 'redux-actions';

const FIRSTOPEN = 'modal/FIRSTOPEN';
const SECONDOPEN = 'modal/SECONDOPEN';
const MODALCLOSE = 'modal/MODALCLOSE';

export const firstModalOpen = createAction(FIRSTOPEN);
export const secondModalOpen = createAction(SECONDOPEN);

export const modalClose = createAction(MODALCLOSE);

const initialState = {
    first: false,
    second: false,
    open: false
};

const dialog = handleActions(
    {
        [FIRSTOPEN]: (state, action) => {
            return {
                ...state, first: true, second: false, open: true
            }
        },
        [SECONDOPEN]: (state, action) => {
            return {
                ...state, first: false, second: true, open: true
            }
        },
        [MODALCLOSE]: (state, action) => {
            return {
                ...state, first: false, second: false, open: false
            }
        }
    },
    initialState
);

export default dialog;