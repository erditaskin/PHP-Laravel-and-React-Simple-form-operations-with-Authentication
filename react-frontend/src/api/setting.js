import api from './api';

export function apiSetting() {
  return api.get('/api/user-settings').then(res => res.data);
}

export function apiSettingUpdate(credentials) {
  return api.post('/api/user-settings/update', credentials).then(res => res.data);
}
