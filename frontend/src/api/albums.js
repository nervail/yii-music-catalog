import { apiRequest } from './client';

/**
 * @param {object} params
 * @param {number} [params.page]
 * @param {number} [params['per-page']]
 * @param {string} [params.name]
 * @param {number} [params.artist_id]
 * @param {string} [params.expand]
 */
export function fetchAlbums(params = {}) {
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

  return apiRequest(`/albums${qs ? `?${qs}` : ''}`);
}

export function fetchAlbum(id, params = {}) {
  const query = new URLSearchParams(params).toString();

  return apiRequest(`/albums/${id}${query ? `?${query}` : ''}`);
}