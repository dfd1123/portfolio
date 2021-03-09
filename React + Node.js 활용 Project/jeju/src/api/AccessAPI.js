import Axios from "axios";

import { Paths } from '../paths/index'

        const URL = Paths.api

export const postUserAccess = async (type) => {
    const query = `${URL}/user/make_access`;
    const config = {
        params: {
        }
    };
    const res = await Axios.post(query, config);
    return res.data
}



export const getUserAccessCount = async (type) => {
    const query = `${URL}/user/get_access_count`;
    const config = {
        params: {
            
        }
    };
    const res = await Axios.get(query, config);
    return res.data
}
