import { apiRequest } from './client';

export function fetchSubscriptions(params = {}) {
  const query = new URLSearchParams();

  Object.entries(params).forEach(([key, value]) => {
    if (value === undefined || value === null || value === '') return;

    if (Array.isArray(value)) {
      value.forEach((v) => query.append(`${key}[]`, v));
    } else {
      query.append(key, value);
    }
  });

  const qs = query.toString();

  return apiRequest(
    `/me/subscriptions${qs ? `?${qs}` : ''}`,
    { auth: true }
  );
}

export function subscribe(artistId) {
  return apiRequest(`/subscribe/${artistId}`, {
    method: 'POST',
    auth: true,
  });
}

export function unsubscribe(artistId) {
  return apiRequest(`/unsubscribe/${artistId}`, {
    method: 'POST',
    auth: true,
  });
}