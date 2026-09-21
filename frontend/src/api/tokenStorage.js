const TOKEN_KEY = 'mc_token';

export function getToken() {
  return localStorage.getItem(TOKEN_KEY);
}

export function setSession(token) {
  if (token) {
    localStorage.setItem(TOKEN_KEY, token);
  } else {
    localStorage.removeItem(TOKEN_KEY);
  }
}
