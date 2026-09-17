import { useState } from 'react';
import { Link, useNavigate, useLocation } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { ApiError } from '../api/client';
import styles from './AuthLayout.module.css';

export default function Login() {
  const { login } = useAuth();
  const navigate = useNavigate();
  const location = useLocation();
  const redirectTo = location.state?.from ?? '/';

  const [form, setForm] = useState({ username: '', password: '' });
  const [submitting, setSubmitting] = useState(false);
  const [formError, setFormError] = useState(null);

  function update(field) {
    return (e) => setForm((prev) => ({ ...prev, [field]: e.target.value }));
  }

  async function handleSubmit(e) {
    e.preventDefault();
    setFormError(null);
    setSubmitting(true);
    try {
      await login(form);
      navigate(redirectTo, { replace: true });
    } catch (err) {
      setFormError(err instanceof ApiError ? err.message : 'Не получилось войти. Попробуй ещё раз.');
    } finally {
      setSubmitting(false);
    }
  }

  return (
    <div className={styles.screen}>
      <aside className={styles.side}>
        <div className={styles.grooves} aria-hidden="true">
          <span />
          <span />
          <span />
        </div>
        <div className={styles.sideText}>
          <h1 className={styles.sideTitle}>С возвращением</h1>
          <p className={styles.sideCopy}>Вход открывает доступ к личным подборкам и управлению каталогом.</p>
        </div>
      </aside>

      <main className={styles.formPane}>
        <form className={styles.card} onSubmit={handleSubmit} noValidate>
          <h2 className={styles.cardTitle}>Вход</h2>

          <div className="field">
            <label htmlFor="username">Имя пользователя</label>
            <input
              id="username"
              name="username"
              autoComplete="username"
              value={form.username}
              onChange={update('username')}
              required
            />
          </div>

          <div className="field">
            <label htmlFor="password">Пароль</label>
            <input
              id="password"
              name="password"
              type="password"
              autoComplete="current-password"
              value={form.password}
              onChange={update('password')}
              required
            />
          </div>

          {formError && <p className="field-error">{formError}</p>}

          <button type="submit" className="btn btn-primary" disabled={submitting}>
            {submitting ? 'Входим…' : 'Войти'}
          </button>

          <p className={styles.switch}>
            Нет аккаунта? <Link to="/signup">Зарегистрироваться</Link>
          </p>
        </form>
      </main>
    </div>
  );
}
