import * as mutations from '../mutation-types';
import axios from '@axios';

export default {

    AUTH_REQUEST({commit}, user) {
        return new Promise((resolve, reject) => {
            commit(mutations.AUTH_REQUEST);
            axios.get('/api/sanctum/csrf-cookie').then((resp) => {
            axios.post('/auth/login', user)
                .then((resp) => {
                    const token = resp.data.token;
                    localStorage.setItem('userToken', token);
                    localStorage.setItem('userData', JSON.stringify(resp.data.user));
                    localStorage.setItem('userMenu', JSON.stringify(resp.data.menu));
                    axios.defaults.headers.common["Authorization"] =`Bearer ${token}`;
                    //localStorage.setItem('userAbility', JSON.stringify([{action: 'manage',subject: 'all',}]))
                    commit(mutations.AUTH_SUCCESS, token);
                    commit(mutations.USER, resp.data.user);
                    commit(mutations.MENU, resp.data.menu);
                    resolve(resp)
                })
                .catch((err) => {
                    commit(mutations.AUTH_ERROR, err)
                    localStorage.removeItem('userToken', '')
                    reject(err)
                })
            })
        })
    },

    AUTH_LOGOUT: ({commit}) => {
        return new Promise((resolve) => {
            const userToken = localStorage.getItem('userToken');
            axios.post('/auth/logout',{'userToken': userToken})
                .then((resp) => {
                    localStorage.removeItem('userToken')
                    localStorage.removeItem('userData')
                    localStorage.removeItem('userStatus')
                    localStorage.removeItem('userMenu')
                    commit(mutations.AUTH_LOGOUT)
                    resolve(resp);
                }).catch((error) => {
                //console.log(error);
                resolve(error)
            });
        })
    }
}
