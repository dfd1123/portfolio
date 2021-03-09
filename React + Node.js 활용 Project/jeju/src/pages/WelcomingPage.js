import React from 'react'

import WelcomingContainer from '../container/WelcomingContainer'
import CongraturationContainer from '../container/CongraturationContainer'

export default ({ match }) => {
    const { mode } = match.params;

    return (
        <>
            {mode === 'congraturation' ? <CongraturationContainer />
            : <WelcomingContainer />}
        </>
    )
}