import axios from "axios";

const axiosIns = axios.create({
  // You can add your headers here
  // ================================
  baseURL: '/'
  //credentials: false
})

axiosIns.interceptors.request.use(
  config => {
    // Do something before request is sent

    const accessToken = localStorage.getItem('userToken')

    // eslint-disable-next-line no-param-reassign
    config.headers.Authorization = `Bearer ${accessToken}`
    config.withCredentials = true

    return config
  },
  error => Promise.reject(error),
)

axiosIns.interceptors.response.use(
  response => {
    if (response.status === 200 || response.status === 201) {
      return Promise.resolve(response);
    } else {
      return Promise.reject(response);
    }
  },
  error => {
    if(error.config.url =="auth/login" || error.config.url =="auth/logout") {
      location.reload()
    }
    if ((error.response.status==401 || error.response.status==419)) {
      switch (error.response.status) {
        case 401:
        case 419:
            
            //store.commit('appConfig/TOGGLE_CONTENT_OVERLAY', false)
            localStorage.removeItem('userToken')
            localStorage.removeItem('userData')
            localStorage.removeItem('userStatus')
            localStorage.removeItem('userMenu')
            window.location.href = "/login";
            
          break;
      }
      return Promise.resolve(error);
    }
    return Promise.reject(error)
  }
);

//Vue.prototype.$http = axiosIns

export default axiosIns