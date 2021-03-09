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
    ); //마지막 좌표 및 레벨
    const { parking } = useSelector((state) => state.parking);
    const {
        parking_town,
        underground_parking,
        ground_parking,
        stated_parking,
    } = useSelector((state) => state.filters);

    const [onLoading, offLoading] = useLoading();

    const map_position = useRef(null); //지도 첫렌더시 좌표
    const map_level = useRef(5); // 디폴트 레벨 -> //4 : 100m 6: 500m 7:1km
    const user_position = useRef({ lat: 0, lng: 0 });
    const slide_view = useRef(false); // 슬라이드 여부
    const arrive_markers = useRef([]); //도착지 마커
    const location_marker = useRef([]); // 유저 위치 마커
    const cluster_marker = useRef(null);
    const kakao_map = useRef(null); //카카오 맵
    const history = useHistory();
    const [on_slide, setOnSlide] = useState(false);
    const [slide_list, setSlideList] = useState([]);

    // 모달을 제어하는 리듀서
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

    //지도 레벨을 조정하는 함수
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

    // 맵 중심좌표를 설정하는 함수
    const setCoordinates = useCallback((lat, lng) => {
        const moveLatLon = new kakao.maps.LatLng(lat, lng);
        kakao_map.current.setCenter(moveLatLon);
    }, []);

    //현재 위치를 받아오는 함수.
    const callGetCoordinates = useCallback(async () => {
        window.setGps = (lat, lng) => {
            // Gps 지정 함수
            if (lat && lng) {
                dispatch(set_position({ lat, lng }));
                setCoordinates(lat, lng);
            }
        };
        const login_os = getMobileOperatingSystem();
        if (login_os === 'Android') {
            // 구글 스토어 기기
            if (typeof window.myJs !== 'undefined') {
                window.myJs.getGps();
                return;
            }
        } else if (login_os === 'iOS') {
            // 애플 앱 스토어 기기
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
        // 브라우저 기기
        if ('geolocation' in navigator) {
            try {
                const p = await getCoordinates();
                const lat = p.coords.latitude;
                const lng = p.coords.longitude;
                window.setGps(lat, lng);
            } catch (e) {
                if (e.code === 3) {
                    // 요청 시간 초과
                } else {
                    // 위치접근 거부
                }
            }
        }
    }, [dispatch, setCoordinates]);

    //내위치 마커를 생성하는 함수
    const createMyLocationMarker = useCallback((lat, lng) => {
        if (location_marker.current.length !== 0) {
            location_marker.current.map((marker) => marker.setMap(null));
            location_marker.current = [];
        }
        const imageSrc = USER_LOCATION_MARKER;
        const imageSize = new kakao.maps.Size(22, 22); // 마커이미지의 크기입니다
        const imageOption = { offset: new kakao.maps.Point(0, 0) }; // 마커이미지의 옵션입니다. 마커의 좌표와 일치시킬 이미지 안에서의 좌표를 설정합니다.
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

    // 도착지 마커를 생성하는 함수.
    const createArriveMarker = useCallback(() => {
        if (arrive_markers.current.length !== 0) {
            arrive_markers.current.map((marker) => marker.setMap(null));
            arrive_markers.current = [];
        }
        const content = `<div class="arrive-overlay"><span>도착지</span></div>`;
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

    //주차장 마커를 생성하는 함수
    const createParkingMarker = useCallback(() => {
        onLoading('parking/GET_LIST');
        if (cluster_marker.current !== null) {
            cluster_marker.current.clear();
        }
        const map = kakao_map.current;

        cluster_marker.current = new kakao.maps.MarkerClusterer({
            map: map, // 마커들을 클러스터로 관리하고 표시할 지도 객체
            averageCenter: true, // 클러스터에 포함된 마커들의 평균 위치를 클러스터 마커 위치로 설정
            minLevel: 5, // 클러스터 할 최소 지도 레벨
            disableClickZoom: true, // 클러스터 마커를 클릭했을 때 지도가 확대되지 않도록 설정한다
            styles: [
                {
                    // calculator 각 사이 값 마다 적용될 스타일을 지정한다
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

        //맵의 중심좌표가 변경되었을 시 이벤트
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

        //슬라이드가 켜진상태로 지도를 클릭하면 슬라이드를 끄는 이벤트
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
        // 주차장 마커 생성
        // 스토리지에서 마지막 user_position을 기준으로 마커데이터 생성 ex) 대구좌표 -> 대구 주변 렌더
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
                클러스터 클릭이벤트
                클러스트를 클릭하면 슬라이드 메뉴 생성
                10개 이상이면 지도 줌 인
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
        // 윈도우 클릭이벤트 넘겨야 하는 주차장 마커 클릭함수
        window.onClickOverlay = (place_id) => {
            history.push(Paths.main.detail + '?place_id=' + place_id);
        };
        offLoading('parking/GET_LIST');
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [area, dispatch, history, parking]);

    //지도를 렌더하는 함수
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

    // 마지막 위치 기준으로 get_area 함수 호출하여 해당지역 주차장 받아오기
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

    // 필터 정보가 저장된 스토리지에 접근하여 상태 설정
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
                // Gps 지정 함수
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
                            //요청 시간 초과
                        } else {
                            //위치접근 거부
                        }
                    });
            }
        }, 2000); // 계속해서 현재 위치를 반복으로 찍음.
        return () => {
            clearInterval(interval);
        };
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, []);

    //필터 정보가 바뀌었을시 주자창 리스트 필터링 하기
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

    //도착지가 변경되었을 시 도착지 마커 생성
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

    //맵 페이지를 unmount 했을시 마지막 위치 기억 -> 이전페이지로 돌아왔을시 레벨과 포지션 유지
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
                        위치를 입력해 주세요.
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
