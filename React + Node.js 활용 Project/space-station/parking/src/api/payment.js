import axios from 'axios';

import { Paths } from '../paths';

export const requestGetPayInfo = async (JWT_TOKEN, place_id, rental_start_time, rental_end_time) => {
    // { headers }: JWT_TOKEN(유저 로그인 토큰)
    // place_id: 결제할 주차공간 id
    // start_time: 대여 시작 시간
    // end_time: 대여 종료 시간

    // * 응답: place: 주차공간 정보(요금, 보증금)

    const URL = Paths.api + "order";
    const options = {
        headers:{
           Authorization: `Bearer ${JWT_TOKEN}`,
        },
        params:{
            place_id,
            rental_start_time,
            rental_end_time
        },
    }
    const response = await axios.get(URL, options);
    return response;
};

export const requestGetCoupon = async (JWT_TOKEN, place_id) => {
    // { headers }: JWT_TOKEN(유저 로그인 토큰)
    // place_id: 결제할 주차공간 id

    // * 응답: coupons: [사용가능한 쿠폰 Array…]

    const URL = Paths.api + "coupon";
    const response = await axios.get(URL, {
        headers:{
            Authorization: `Bearer ${JWT_TOKEN}`,
         },
         params:{
             place_id,
         },
    });

    return response.data;
};