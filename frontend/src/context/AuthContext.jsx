import { createContext, useCallback, useContext, useState } from 'react';
import { login as loginRequest, signup as signupRequest, logout as logoutRequest } from '../api/auth';
import { getToken, getStoredUsername, setSession } from '../api/tokenStorage';

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
  const [token, setToken] = useState(() => getToken());
  const [username, setUsername] = useState(() => getStoredUsername());

  const applySession = useCallback((nextToken, nextUsername) => {
    setSession(nextToken, nextUsername);
    setToken(nextToken);
    setUsername(nextToken ? nextUsername : null);
  }, []);

  const login = useCallback(
    async (credentials) => {
      const data = await loginRequest(credentials);
      applySession(data.access_token, data.username);
      return data;
    },
    [applySession]
  );

  const signup = useCallback(
    async (payload) => {
      const data = await signupRequest(payload);
      return data;
    },
    []
  );

  const logout = useCallback(async () => {
    try {
      await logoutRequest();
    } finally {
      applySession(null, null);
    }
  }, [applySession]);

  const value = {
    token,
    username,
    isAuthenticated: Boolean(token),
    login,
    signup,
    logout,
  };

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
}

export function useAuth() {
  const ctx = useContext(AuthContext);
  if (!ctx) throw new Error('useAuth должен использоваться внутри <AuthProvider>');
  return ctx;
}
