import axios from 'axios'

axios.defaults.headers.common['Accept'] = 'application/json'
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

// axios 요청 시마다 토큰 최신화
axios.interceptors.request.use(config => {
  // 이전에 발급받은 토큰이 있으면
  if (localStorage.CmsAccessToken) {
    config.headers['Authorization'] = `Bearer ${localStorage.CmsAccessToken}`
  }
  
  return config
})

// axios 요청 응답코드가 401(인증안됨) 이면 세션 만료 표시
axios.interceptors.response.use(
  function (response) {
    return response
  },
  function (error) {
    if (error.response && error.response.status === 401) {
      if (localStorage.CmsAccessToken && !error.response.config.url.includes('api/v1/auth')) {
        localStorage.removeItem('CmsAccessToken')
        localStorage.removeItem('CmsRefreshToken')
        localStorage.removeItem('CmsUserState')

        window.location.replace('/login')
      }
    } else if (error.code === 'ECONNABORTED') {
      // alert("네트워크 접속 상태가 불안정합니다.");
    }

    return Promise.reject(error)
  }
)

export default axios
