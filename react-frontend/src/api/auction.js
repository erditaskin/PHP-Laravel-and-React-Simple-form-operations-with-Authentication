import api from './api';

export function apiAuctionList(credentials) {
  return api.get('/api/auction/list?' + new URLSearchParams(credentials).toString()).then(res => res.data);
}

export function apiAuctionDetail(auction) {
  return api.get(`/api/auction/detail/${auction}`).then(res => res.data);
}

export function apiMakeBid(auctionId, credentials) {
  return api.post(`/api/auction/bid/${auctionId}`, credentials).then(res => res.data);
}

export function apiActivateAutoBid(auctionId, credentials) {
  return api.post(`/api/auction/auto-bid/${auctionId}`, credentials).then(res => res.data);
}