import React from 'react';

const Like = ({ status }) => {
    return (
        <svg
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="21.854"
            viewBox="0 0 24 21.854"
        >
            <path
                d="M23.635,5.754A6.728,6.728,0,0,0,11.988,3.833,6.723,6.723,0,0,0,6.694,1.257h0A6.771,6.771,0,0,0,1.187,4.119,6.431,6.431,0,0,0,.047,7.1,8.13,8.13,0,0,0,.3,10.124a15.949,15.949,0,0,0,2.717,5.247,31.226,31.226,0,0,0,8.528,7.456l.443.278.443-.278c4.563-2.866,7.748-5.893,9.736-9.252a11.6,11.6,0,0,0,1.822-5.261A6.675,6.675,0,0,0,23.635,5.754ZM11.99,21.135a29.057,29.057,0,0,1-7.641-6.767A14.293,14.293,0,0,1,1.913,9.7a6.479,6.479,0,0,1-.211-2.4A4.782,4.782,0,0,1,2.55,5.077a5.062,5.062,0,0,1,8.69.682l.749,1.526.747-1.527a5.07,5.07,0,0,1,9.326.547,5.018,5.018,0,0,1,.263,1.924,10.013,10.013,0,0,1-1.591,4.5C18.949,15.744,16.085,18.5,11.99,21.135Z"
                transform="translate(0 -1.252)"
                fill={status ? "#ff7daa": "#aaa"}
            />
        </svg>
    );
};

export default Like;
