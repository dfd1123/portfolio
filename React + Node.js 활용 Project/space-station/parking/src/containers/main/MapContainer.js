/*global kakao*/
import React, {
    useEffect,
    useReducer,
    useRef,
    useState,
    useCallback,
} from 'react';

import { useSelector, useDispatch } from 'react-redux';
import { useHistory } from 'react-router-dom';

import { Paths } from '../../paths';

//styles
import styles from './MapContainer.module.scss';
import './overlay.scss';
import cn from 'classnames/bind';
import { ButtonBase, IconButton } from '@material-ui/core';

//main icons
import SEARCH from '../../static/asset/svg/main/search.svg';
import ZOOMIN from '../../static/asset/svg/main/plus.svg';
import ZOOMOUT from '../../static/asset/svg/main/minus.svg';
import FILTER from '../../static/asset/svg/main/filter.svg';
import POSITION from '../../static/asset/svg/main/location.svg';
// import TIME from '../../static/asset/svg/main/time.svg';
import BOOKMARK from '../../static/asset/svg/main/like.svg';

//marker
// import PARKING_MARKER from '../../static/asset/svg/main/marker2.svg';
// import ARRIVED_MARKER from '../../static/asset/svg/main/arrive_marker.svg';
import USER_LOCATION_MARKER from '../../static/asset/svg/main/mylocation.svg';

//components
import Sidebar from 'react-sidebar'
import Aside from '../../components/aside/Aside';
import BottomModal from '../../components/nav/BottomModal';
import ParkingList from '../../components/items/ParkingList';
import CircleButton from '../../components/button/CircleButton';
import AddressModal from '../../components/modal/AddressModal';
import BookmarkModal from '../../components/modal/BookmarkModal';

//lib
import { getDistanceFromLatLonInKm } from '../../lib/distance';
import { getMobileOperatingSystem } from '../../lib/os';
//action
import { set_position, set_level, get_area } from '../../store/main/position';
import { get_list } from '../../store/main/parking';
import { set_filters } from '../../store/main/filters';

//api

import { getCoordinates } from '../../api/address';
//hooks
import useLoading from '../../hooks/useLoading';

const cx = cn.bind(styles);

const MapContainer = ({ modal }) => {
    const dispatch = useDispatch();
    const { position, level, address, arrive, area } = useSelector(
        (state) => state.position,
    ); //????????? ?????? ??? ??????
    const { parking } = useSelector((state) => state.parking);
    const {
        parking_town,
        underground_parking,
        ground_parking,
        stated_parking,
    } = useSelector((state) => state.filters);

    const [onLoading, offLoading] = useLoading();

    const map_position = useRef(null); //?????? ???????????? ??????
    const map_level = useRef(5); // ????????? ?????? -> //4 : 100m 6: 500m 7:1km
    const user_position = useRef({ lat: 0, lng: 0 });
    const slide_view = useRef(false); // ???????????? ??????
    const arrive_markers = useRef([]); //????????? ??????
    const location_marker = useRef([]); // ?????? ?????? ??????
    const cluster_marker = useRef(null);
    const kakao_map = useRef(null); //????????? ???
    const history = useHistory();
    const [on_slide, setOnSlide] = useState(false);
    const [slide_list, setSlideList] = useState([]);

    // ????????? ???????????? ?????????
    const [modalState, dispatchHandle] = useReducer(
        (state, action) => {
            return {
                ...state,
                [action.type]: action.payload,
            };
        },
        { aside_: false, filter_: false },
    );

    const setArriveLevel = (index) => {
        let level = 1;
        switch (index) {
            case 0:
                level = 4;
                break;
            case 1:
                level = 6;
                break;
            case 2:
                level = 7;
                break;
            default:
                level = 5;
                break;
        }
        kakao_map.current.setLevel(level, {
            animate: {
                duration: 300,
            },
        });
    };

    //?????? ????????? ???????????? ??????
    const zoomMap = useCallback(
        (type) => {
            let level = kakao_map.current.getLevel();
            level = type === 'zoomin' ? level - 1 : level + 1;
            kakao_map.current.setLevel(level, {
                animate: {
                    duration: 300,
                },
            });
            dispatch(set_level(level));
        },
        [dispatch],
    );

    // ??? ??????????????? ???????????? ??????
    const setCoordinates = useCallback((lat, lng) => {
        const moveLatLon = new kakao.maps.LatLng(lat, lng);
        kakao_map.current.setCenter(moveLatLon);
    }, []);

    //?????? ????????? ???????????? ??????.
    const callGetCoordinates = useCallback(async () => {
        window.setGps = (lat, lng) => {
            // Gps ?????? ??????
            if (lat && lng) {
                dispatch(set_position({ lat, lng }));
                setCoordinates(lat, lng);
            }
        };
        const login_os = getMobileOperatingSystem();
        if (login_os === 'Android') {
            // ?????? ????????? ??????
            if (typeof window.myJs !== 'undefined') {
                window.myJs.getGps();
                return;
            }
        } else if (login_os === 'iOS') {
            // ?????? ??? ????????? ??????
            if (typeof window.webkit !== 'undefined') {
                if (typeof window.webkit.messageHandlers !== 'undefined') {
                    if (
                        typeof window.webkit.messageHandlers.getGps !==
                        'undefined'
                    ) {
                        window.webkit.messageHandlers.getGps.postMessage('');
                    }
                    return;
                }
            }
        }
        // ???????????? ??????
        if ('geolocation' in navigator) {
            try {
                const p = await getCoordinates();
                const lat = p.coords.latitude;
                const lng = p.coords.longitude;
                window.setGps(lat, lng);
            } catch (e) {
                if (e.code === 3) {
                    // ?????? ?????? ??????
                } else {
                    // ???????????? ??????
                }
            }
        }
    }, [dispatch, setCoordinates]);

    //????????? ????????? ???????????? ??????
    const createMyLocationMarker = useCallback((lat, lng) => {
        if (location_marker.current.length !== 0) {
            location_marker.current.map((marker) => marker.setMap(null));
            location_marker.current = [];
        }
        const imageSrc = USER_LOCATION_MARKER;
        const imageSize = new kakao.maps.Size(22, 22); // ?????????????????? ???????????????
        const imageOption = { offset: new kakao.maps.Point(0, 0) }; // ?????????????????? ???????????????. ????????? ????????? ???????????? ????????? ???????????? ????????? ???????????????.
        const markerImage = new kakao.maps.MarkerImage(
            imageSrc,
            imageSize,
            imageOption,
        );
        const markerPosition = new kakao.maps.LatLng(lat, lng);
        const marker = new kakao.maps.Marker({
            position: markerPosition,
            image: markerImage,
        });
        marker.setMap(kakao_map.current);
        location_marker.current.push(marker);
    }, []);

    // ????????? ????????? ???????????? ??????.
    const createArriveMarker = useCallback(() => {
        if (arrive_markers.current.length !== 0) {
            arrive_markers.current.map((marker) => marker.setMap(null));
            arrive_markers.current = [];
        }
        const content = `<div class="arrive-overlay"><span>?????????</span></div>`;
        var customOverlay = new kakao.maps.CustomOverlay({
            map: kakao_map.current,
            position: new kakao.maps.LatLng(arrive.lat, arrive.lng),
            content: content,
            yAnchor: 1,
            clickable: true,
            zIndex: 1600,
        });
        customOverlay.setMap(kakao_map.current);
        arrive_markers.current.push(customOverlay);
        setCoordinates(arrive.lat, arrive.lng);
    }, [arrive.lat, arrive.lng, setCoordinates]);

    //????????? ????????? ???????????? ??????
    const createParkingMarker = useCallback(() => {
        onLoading('parking/GET_LIST');
        if (cluster_marker.current !== null) {
            cluster_marker.current.clear();
        }
        const map = kakao_map.current;

        cluster_marker.current = new kakao.maps.MarkerClusterer({
            map: map, // ???????????? ??????????????? ???????????? ????????? ?????? ??????
            averageCenter: true, // ??????????????? ????????? ???????????? ?????? ????????? ???????????? ?????? ????????? ??????
            minLevel: 5, // ???????????? ??? ?????? ?????? ??????
            disableClickZoom: true, // ???????????? ????????? ???????????? ??? ????????? ???????????? ????????? ????????????
            styles: [
                {
                    // calculator ??? ?????? ??? ?????? ????????? ???????????? ????????????
                    width: '40px',
                    height: '40px',
                    background: 'rgba(34, 34, 34, .8)',
                    borderRadius: '30px',
                    color: '#fff',
                    border: '1px solid white',
                    boxSizing: 'border-box',
                    fontSize: '15px',
                    textAlign: 'center',
                    fontWeight: 'bold',
                    lineHeight: '40px',
                },
            ],
        });

        //?????? ??????????????? ??????????????? ??? ?????????
        kakao.maps.event.addListener(map, 'center_changed', () => {
            const level = map.getLevel();
            const latlng = map.getCenter();
            map_level.current = level;
            map_position.current.lat = latlng.getLat();
            map_position.current.lng = latlng.getLng();
            const { lat, lng } = map_position.current;
            dispatch(get_area({ lat, lng }));
            const new_position = { lat, lng };
            localStorage.setItem('position', JSON.stringify(new_position));
        });

        //??????????????? ??????????????? ????????? ???????????? ??????????????? ?????? ?????????
        kakao.maps.event.addListener(map, 'click', (mouseEvent) => {
            if (slide_view.current) {
                slide_view.current = !slide_view.current;
                setOnSlide(slide_view.current);
            }
        });
        const markdata = parking.filter((item) => {
            return (
                item.addr.indexOf(area['type1']) !== -1 ||
                item.addr.indexOf(area['type2']) !== -1
            );
        });
        // ????????? ?????? ??????
        // ?????????????????? ????????? user_position??? ???????????? ??????????????? ?????? ex) ???????????? -> ?????? ?????? ??????
        const storage_position = JSON.parse(
            sessionStorage.getItem('user_position'),
        );
        if (storage_position) {
            const data = markdata.map((el) => {
                const distance = getDistanceFromLatLonInKm(
                    el.lat,
                    el.lng,
                    storage_position.lat,
                    storage_position.lng,
                );
                const content = `<div onclick="onClickOverlay(${
                    el.place_id
                })" class="custom-overlay" title=${JSON.stringify(
                    el,
                )} ><span>${distance}Km</span></div>`;
                var customOverlay = new kakao.maps.CustomOverlay({
                    map: map,
                    position: new kakao.maps.LatLng(el.lat, el.lng),
                    content: content,
                    yAnchor: 1,
                    clickable: true,
                    zIndex: 1600,
                });
                customOverlay.setMap(map);
                return customOverlay;
            });
            cluster_marker.current.addMarkers(data);

            /*
                ???????????? ???????????????
                ??????????????? ???????????? ???????????? ?????? ??????
                10??? ???????????? ?????? ??? ???
            */
            kakao.maps.event.addListener(
                cluster_marker.current,
                'clusterclick',
                (cluster) => {
                    const overlays = cluster.getMarkers();

                    if (overlays.length > 10) {
                        var level = map.getLevel() - 1;
                        map.setLevel(level, {
                            anchor: cluster.getCenter(),
                            animate: 300,
                        });
                    } else {
                        slide_view.current = !slide_view.current;

                        const slides = overlays.map((overlay) => {
                            const data = overlay.getContent();
                            const t_index = data.indexOf('title=');
                            const close_index = data.indexOf('>');
                            const str = data.substring(
                                t_index + 6,
                                close_index,
                            );
                            return JSON.parse(str);
                        });
                        setSlideList(slides);
                        setOnSlide(slide_view.current);
                    }
                },
            );
        }
        // ????????? ??????????????? ????????? ?????? ????????? ?????? ????????????
        window.onClickOverlay = (place_id) => {
            history.push(Paths.main.detail + '?place_id=' + place_id);
        };
        offLoading('parking/GET_LIST');
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [area, dispatch, history, parking]);

    //????????? ???????????? ??????
    const mapRender = useCallback(() => {
        let container = document.getElementById('map');
        let lat = map_position.current.lat;
        let lng = map_position.current.lng;
        let options = {
            center: new kakao.maps.LatLng(lat, lng),
            level: level !== 0 ? level : map_level.current,
        };
        const map = new kakao.maps.Map(container, options);
        map.setMaxLevel(8);
        kakao_map.current = map;
    }, [level]);

    // ????????? ?????? ???????????? get_area ?????? ???????????? ???????????? ????????? ????????????
    useEffect(() => {
        const storage_position = JSON.parse(localStorage.getItem('position'));
        if (storage_position && storage_position.lat && storage_position.lng) {
            map_position.current = storage_position;
            const { lat, lng } = map_position.current;
            dispatch(get_area({ lat, lng }));
        } else {
            const init_position = {
                lat: 35.8360328674316,
                lng: 128.5743408203125,
            };
            map_position.current = init_position;
            const { lat, lng } = init_position;
            localStorage.setItem('position', JSON.stringify(init_position));
            dispatch(get_area({ lat, lng }));
        }
    }, [dispatch]);

    // ?????? ????????? ????????? ??????????????? ???????????? ?????? ??????
    useEffect(() => {
        const storage_filter = JSON.parse(localStorage.getItem('filter_data'));
        if (storage_filter) {
            const {
                parking_town,
                underground_parking,
                ground_parking,
                stated_parking,
            } = storage_filter;
            dispatch(
                set_filters({ type: 'parking_town', value: parking_town }),
            );
            dispatch(
                set_filters({
                    type: 'underground_parking',
                    value: underground_parking,
                }),
            );
            dispatch(
                set_filters({ type: 'ground_parking', value: ground_parking }),
            );
            dispatch(
                set_filters({ type: 'stated_parking', value: stated_parking }),
            );
        } else {
            const init_filter = {
                parking_town: true,
                underground_parking: true,
                ground_parking: true,
                stated_parking: true,
            };
            localStorage.setItem('filter_data', JSON.stringify(init_filter));
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);

    useEffect(() => {
        mapRender();
        const login_os = getMobileOperatingSystem();
        const interval = setInterval(() => {
            window.setGps = (latitude, longitude) => {
                // Gps ?????? ??????
                const p = {
                    lat: latitude,
                    lng: longitude,
                };
                sessionStorage.setItem('user_position', JSON.stringify(p));
                const storage_position = JSON.parse(
                    sessionStorage.getItem('user_position'),
                );
                if (storage_position) {
                    if (
                        user_position.current.lat !== storage_position.lat &&
                        user_position.current.lng !== storage_position.lng
                    ) {
                        user_position.current.lat = storage_position.lat;
                        user_position.current.lng = storage_position.lng;
                        createMyLocationMarker(latitude, longitude);
                    }
                } else {
                    createMyLocationMarker(latitude, longitude);
                }
            };
            if (login_os === 'Android') {
                if (typeof window.myJs !== 'undefined') {
                    window.myJs.getGps();
                    return;
                }
            } else if (login_os === 'iOS') {
                if (typeof window.webkit !== 'undefined') {
                    if (typeof window.webkit.messageHandlers !== 'undefined') {
                        if (
                            typeof window.webkit.messageHandlers.getGps !==
                            'undefined'
                        ) {
                            window.webkit.messageHandlers.getGps.postMessage(
                                '',
                            );
                            return;
                        }
                    }
                }
            }
            if ('geolocation' in navigator) {
                getCoordinates()
                    .then((result) => {
                        const lat = result.coords.latitude;
                        const lng = result.coords.longitude;
                        window.setGps(lat, lng);
                    })
                    .catch((e) => {
                        if (e.code === 3) {
                            //?????? ?????? ??????
                        } else {
                            //???????????? ??????
                        }
                    });
            }
        }, 2000); // ???????????? ?????? ????????? ???????????? ??????.
        return () => {
            clearInterval(interval);
        };
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);

    //?????? ????????? ??????????????? ????????? ????????? ????????? ??????
    useEffect(() => {
        const { lat, lng } = map_position.current;
        let filter_arr = [];
        if (parking_town) {
            filter_arr.push(0);
        }
        if (underground_parking) {
            filter_arr.push(1);
        }
        if (ground_parking) {
            filter_arr.push(2);
        }
        if (stated_parking) {
            filter_arr.push(3);
        }
        dispatch(get_list({ lat, lng, range: 3000, filter: filter_arr }));
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [parking_town, underground_parking, ground_parking, stated_parking]);

    useEffect(createParkingMarker, [createParkingMarker]);

    //???????????? ??????????????? ??? ????????? ?????? ??????
    useEffect(() => {
        if (address) {
            createArriveMarker();
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [address, arrive]);

    useEffect(() => {
        if (position.lat !== 0 && position.lng !== 0) {
            setCoordinates(position.lat, position.lng);
        }
    }, [position, setCoordinates]);

    //??? ???????????? unmount ????????? ????????? ?????? ?????? -> ?????????????????? ??????????????? ????????? ????????? ??????
    useEffect(() => {
        return () => {
            dispatch(set_position(map_position.current));
            dispatch(set_level(map_level.current));
        };
    }, [dispatch]);

    return (
        <Sidebar
            sidebarClassName={styles['aside']}
            overlayClassName={styles['dim']}
            open={modalState.aside_}
            touch={true}
            transitions={true}
            touchHandleWidth={20}
            dragToggleDistance={30}
            onSetOpen={() => dispatchHandle({ type: 'aside_', payload: false })}
            sidebar={
                <Aside
                    open={modalState.aside_}
                    handleClose={() => dispatchHandle({ type: 'aside_', payload: false })}
                />
            }
        >
            <div className={styles['container']}>
                <div className={styles['content']}>
                    <div
                        id="map"
                        style={{ width: '100%', height: '100vh', zIndex: 1 }}
                    />
                </div>
                <ButtonBase
                    className={styles['menu']}
                    onClick={() => {
                        dispatchHandle({ type: 'aside_', payload: true });
                    }}
                >
                    <div className={styles['line-box']}>
                        <div className={styles['line']} />
                        <div className={styles['line']} />
                        <div className={styles['line']} />
                    </div>
                </ButtonBase>
                <div className={styles['search']}>
                    <ButtonBase
                        className={styles['search-box']}
                        onClick={() =>
                            history.push(Paths.main.index + '/address')
                        }
                    >
                        ????????? ????????? ?????????.
                    </ButtonBase>
                    <IconButton className={styles['search-btn']}>
                        <img src={SEARCH} alt="search" />
                    </IconButton>
                </div>
                <div className={cx('side-bar', 'left')}>
                    <CircleButton
                        src={ZOOMIN}
                        onClick={() => {
                            zoomMap('zoomin');
                        }}
                    />
                    <CircleButton
                        src={ZOOMOUT}
                        onClick={() => {
                            zoomMap('zoomout');
                        }}
                    />
                </div>
                <div className={cx('side-bar', 'right')}>
                    <CircleButton
                        src={FILTER}
                        onClick={() => {
                            dispatchHandle({ type: 'filter_', payload: true });
                        }}
                    />
                    <CircleButton src={POSITION} onClick={callGetCoordinates} />
                    <CircleButton
                        src={BOOKMARK}
                        onClick={() =>
                            history.push(Paths.main.index + '/bookmark')
                        }
                    />
                </div>
                <ParkingList
                    onClick={(id) => {
                        slide_view.current = false;
                        setOnSlide(false);
                        setTimeout(() => {
                            history.push(Paths.main.detail + '?place_id=' + id);
                        }, 600);
                    }}
                    view={on_slide}
                    slide_list={slide_list}
                />
            </div>
            <BottomModal
                open={modalState.filter_}
                handleClose={() => {
                    dispatchHandle({ type: 'filter_', payload: false });
                }}
            />
            <BookmarkModal
                open={modal === 'bookmark'}
                handleClose={() => history.goBack()}
            />
            <AddressModal
                open={modal === 'address'}
                handleClose={() => history.goBack()}
                setArriveLevel={setArriveLevel}
            />
        </Sidebar>
    );
};

export default MapContainer;
