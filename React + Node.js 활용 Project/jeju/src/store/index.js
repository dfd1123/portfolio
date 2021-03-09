import { combineReducers } from "redux";

import dialog from './dialog';
import loading from './loading';
import modal from './modal';
import language from './language';
import exhibition from './exhibition'

const rootReducer = combineReducers({
    loading,
    dialog,
    modal,
    language,
    exhibition
});

export default rootReducer;
