import { createContext, useCallback, useContext, useState, useEffect } from 'react';
import { login as loginRequest, signup as signupRequest, logout as logoutRequest, getMe } from '../api/auth';
import { getToken, setSession } from '../api/tokenStorage';

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
  const [token, setToken] = useState(() => getToken());
  const [user, setUser] = useState(null);
  const [isAuthLoading, setIsAuthLoading] = useState(true);

  const applySession = useCallback((nextToken, nextUsername) => {
    setSession(nextToken, nextUsername);
    setToken(nextToken);
  }, []);

  const login = useCallback(
    async (credentials) => {
      const data = await loginRequest(credentials);

      applySession(data.access_token);
      
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
      applySession(null);
      setUser(null);
    }
  }, [applySession]);

  useEffect(() => {
    async function restoreSession() {
      if (!token) {
        setIsAuthLoading(false);
        return;
      }

      try {
        const currentUser = await getMe();
        setUser(currentUser);
      } catch {
        applySession(null);
        setUser(null);
      } finally {
        setIsAuthLoading(false);
      }
    }

    restoreSession();
  }, [token, applySession]);

  const value = {
    token,
    user,
    isAuthenticated: Boolean(token),
    isAuthLoading,
    login,
    signup,
    logout,
  };

  return (
    <AuthContext.Provider value={value}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const ctx = useContext(AuthContext);

  if (!ctx) {
    throw new Error(
      'useAuth должен использоваться внутри <AuthProvider>'
    );
  }

  return ctx;
}