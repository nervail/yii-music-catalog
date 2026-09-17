import { getToken } from './tokenStorage';

const DEV_PREFIX = '/api';
const CONFIGURED_BASE = import.meta.env.VITE_API_BASE_URL;

function resolveBase() {
  // В разработке без явно заданного VITE_API_BASE_URL используем
  // относительный /api — его перехватывает прокси из vite.config.js.
  if (import.meta.env.DEV && !CONFIGURED_BASE) {
    return DEV_PREFIX;
  }
  const root = (CONFIGURED_BASE || 'http://admin.music.local').replace(/\/$/, '');
  return `${root}/api`;
}

export class ApiError extends Error {
  constructor(message, errors, status) {
    super(message);
    this.name = 'ApiError';
    this.errors = errors;
    this.status = status;
  }
}

function extractMessage(errors) {
  if (!errors) return null;
  if (typeof errors === 'string') return errors;
  if (Array.isArray(errors.system)) return errors.system[0];
  const firstListKey = Object.keys(errors).find((key) => Array.isArray(errors[key]));
  return firstListKey ? errors[firstListKey][0] : null;
}

/**
 * @param {string} path - путь вида "/items" (без /api, он уже в base)
 * @param {object} options
 * @param {'GET'|'POST'|'PUT'|'DELETE'} [options.method]
 * @param {object} [options.body]
 * @param {boolean} [options.auth] - приложить Authorization: Bearer <token>
 */
export async function apiRequest(path, { method = 'GET', body, auth = false, headers = {} } = {}) {
  const base = resolveBase();
  const finalHeaders = { 'Content-Type': 'application/json', ...headers };

  if (auth) {
    const token = getToken();
    if (token) finalHeaders.Authorization = `Bearer ${token}`;
  }

  let response;
  try {
    response = await fetch(`${base}${path}`, {
      method,
      headers: finalHeaders,
      body: body ? JSON.stringify(body) : undefined,
    });
  } catch {
    throw new ApiError('Не удалось связаться с сервером. Проверь подключение и адрес API.', null, 0);
  }

  let payload = null;
  try {
    payload = await response.json();
  } catch {
    // тело могло быть пустым (например, 204)
  }

  if (!response.ok || !payload || payload.success === false) {
    const errors = payload?.errors ?? null;
    const message = extractMessage(errors) || `Сервер ответил с ошибкой (${response.status})`;
    throw new ApiError(message, errors, response.status);
  }

  return payload.data;
}
