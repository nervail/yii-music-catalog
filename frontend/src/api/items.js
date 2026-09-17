import { apiRequest } from './client';

/**
 * @param {object} params
 * @param {number} [params.page]
 * @param {number} [params['per-page']]
 * @param {string} [params.name]
 * @param {string} [params.description]
 * @param {number} [params.artist_id]
 * @param {string} [params.status]
 * @param {number[]} [params.genre_ids]
 * @param {string} [params.expand]
 */
export function fetchItems(params = {}) {
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
  return apiRequest(`/items${qs ? `?${qs}` : ''}`);
}

export function fetchItem(id, params = {}) {
  const query = new URLSearchParams(params).toString();
  return apiRequest(`/items/${id}${query ? `?${query}` : ''}`);
}
