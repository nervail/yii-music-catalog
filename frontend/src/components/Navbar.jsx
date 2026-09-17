import { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import styles from './Navbar.module.css';

export default function Navbar() {
  const { isAuthenticated, username, logout } = useAuth();
  const navigate = useNavigate();
  const [loggingOut, setLoggingOut] = useState(false);

  async function handleLogout() {
    setLoggingOut(true);
    try {
      await logout();
      navigate('/');
    } finally {
      setLoggingOut(false);
    }
  }

  return (
    <header className={styles.header}>
      <Link to="/" className={styles.brand}>
        <span className={styles.brandMark} aria-hidden="true" />
        <span className={styles.brandName}>Каталог</span>
      </Link>

      <nav className={styles.right}>
        {isAuthenticated ? (
          <>
            <span className={styles.user}>{username}</span>
            <button type="button" className="btn btn-ghost" onClick={handleLogout} disabled={loggingOut}>
              {loggingOut ? 'Выходим…' : 'Выйти'}
            </button>
          </>
        ) : (
          <>
            <Link to="/login" className="btn btn-ghost">
              Войти
            </Link>
            <Link to="/signup" className="btn btn-primary">
              Регистрация
            </Link>
          </>
        )}
      </nav>
    </header>
  );
}
