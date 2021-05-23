import axios from 'axios';
import config from '../config.json';

const headers = {
  'Content-Type': 'application/json',
};

const user = localStorage.getItem('user_id');
if (user) {
  headers['x-auth-user'] = user;
}

const api = axios.create({
  baseURL: config[process.env.NODE_ENV],
  headers: headers,
});

api.interceptors.response.use(
  (response) => {
    return response;
  },
  function (error) {
    if (error.response.status === 403 && window.location.pathname !== '/login') {
      localStorage.clear();

      return (window.location.href = '/login');
    } else if (error.response.status >= 500 || error.response.status === 404) {
      return (window.location.href = `/error/${error.response.status}`);
    }

    return Promise.reject(error.response);
  }
);

export default api;