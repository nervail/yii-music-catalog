import { apiRequest } from './client';

export function fetchArtists(params = {}) {
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

  return apiRequest(`/artists${qs ? `?${qs}` : ''}`);
}

export function fetchArtist(id, params = {}) {
  const query = new URLSearchParams(params).toString();

  return apiRequest(
    `/artists/${id}${query ? `?${query}` : ''}`
  );
}