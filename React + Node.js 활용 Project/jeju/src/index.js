import 'core-js/stable';
import 'regenerator-runtime/runtime';
import 'react-app-polyfill/ie11';
import 'react-app-polyfill/ie9';
import 'react-app-polyfill/stable';
import React from 'react';
import ReactDOM from 'react-dom';
import App from './App';
import { BrowserRouter, Route } from 'react-router-dom';
import * as serviceWorker from './serviceWorker';
/* Library */

import { createStore } from "redux";
import { Provider } from 'react-redux';

import rootReducer  from './store';
/* Redux */


const store = createStore(
    rootReducer
);

ReactDOM.render(
    <Provider store={store}>
        <BrowserRouter>
            <Route path="/:language?" component={App} />
        </BrowserRouter>
    </Provider>,
    document.getElementById('root'),
);

serviceWorker.unregister();
