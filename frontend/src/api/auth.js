import { apiRequest } from './client';

export function signup({ username, password, email }) {
  return apiRequest('/signup', { method: 'POST', body: { username, password, email } });
}

export function login({ username, password }) {
  return apiRequest('/login', { method: 'POST', body: { username, password } });
}

export function logout() {
  return apiRequest('/logout', { method: 'POST', auth: true });
}

export function verifyEmail(token) {
  return apiRequest('/verify-email', { method: 'POST', body: { token } });
}