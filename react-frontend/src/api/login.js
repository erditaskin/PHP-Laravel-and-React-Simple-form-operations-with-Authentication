import api from './api';

export const isLogged = localStorage.getItem('user_id') !== null;

export function getUser() {
  return localStorage.getItem('user_id');
}

export function getUserName() {
  return localStorage.getItem('username');
}

export function setUserToStorage(user_id, username) {
  localStorage.setItem('user_id', user_id);
  localStorage.setItem('username', username);
}

export function logout() {
  localStorage.removeItem('user_id');
  localStorage.removeItem('username');
}

export function apiLogin(credentials) {
  return api.post('/api/login', credentials).then(res => res.data);
}
