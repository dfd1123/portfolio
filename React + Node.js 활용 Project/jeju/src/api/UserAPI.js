import Axios from 'axios'

import { Paths } from '../paths/index'

const URL = Paths.api

export const postUserEvent = async ({ name, position, email, phone }) => {
    const query = `${URL}/user/add_attend_user`

    const formData = new FormData();
    formData.append('name', name);
    formData.append('position', position);
    formData.append('email', email);
    formData.append('phone', phone);

    const res = await Axios.post(query, formData);

    return res.data;
}