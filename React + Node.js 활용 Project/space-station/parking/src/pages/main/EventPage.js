import React from 'react';
import { Switch, Route } from 'react-router-dom';
/* Library */

import EventListContainer from '../../containers/main/event/EventListContainer';
import EventDetailContainer from '../../containers/main/event/EventDetailContainer';
/* Containers */

import { Paths } from '../../paths';
/* Paths */

const EventPage = ({ history }) => {
    return (
        <Switch>
            <Route path={Paths.main.event.list} component={EventListContainer} />
            <Route path={Paths.main.event.detail} component={EventDetailContainer} />
            <Route render={() => history.replace(Paths.main.event.list)} />
        </Switch>
    );
};

export default EventPage;