import { useState } from 'react';
import { Link } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import { ApiError } from '../api/client';
import styles from './AuthLayout.module.css';

export default function Signup() {
  const { signup } = useAuth();

  const [form, setForm] = useState({ username: '', email: '', password: '' });
  const [fieldErrors, setFieldErrors] = useState({});
  const [formError, setFormError] = useState(null);
  const [submitting, setSubmitting] = useState(false);
  const [registered, setRegistered] = useState(false);

  function update(field) {
    return (e) => setForm((prev) => ({ ...prev, [field]: e.target.value }));
  }

  async function handleSubmit(e) {
    e.preventDefault();
    setFormError(null);
    setFieldErrors({});
    setSubmitting(true);

    try {
      await signup(form);
      setRegistered(true);
    } catch (err) {
      if (err instanceof ApiError && err.errors && typeof err.errors === 'object') {
        const { username, email, password } = err.errors;

        setFieldErrors({
          username: username?.[0],
          email: email?.[0],
          password: password?.[0],
        });

        if (!username && !email && !password) {
          setFormError(err.message);
        }
      } else {
        setFormError('Не получилось зарегистрироваться. Попробуй ещё раз.');
      }
    } finally {
      setSubmitting(false);
    }
  }

  if (registered) {
    return (
      <div className={styles.screen}>
        <aside className={styles.side}>
          <div className={styles.grooves} aria-hidden="true">
            <span />
            <span />
            <span />
          </div>

          <div className={styles.sideText}>
            <h1 className={styles.sideTitle}>Почти готово</h1>
            <p className={styles.sideCopy}>
              Осталось подтвердить адрес электронной почты.
            </p>
          </div>
        </aside>
        <main className={styles.formPane}>
          <div className={styles.card}>
            <h2 className={styles.cardTitle}>Подтверди почту</h2>

            <div className={styles.confirmText}>
              <p>
                Мы отправили письмо с ссылкой для подтверждения на:
              </p>

              <p>
                <strong>{form.email}</strong>
              </p>

              <p>
                Перейди по ссылке из письма, после чего ты сможешь войти
                в аккаунт.
              </p>
            </div>

            <p className={styles.switch}>
              Уже подтвердил почту? <Link to="/login">Войти</Link>
            </p>
          </div>
        </main>
      </div>
    );
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
          <h1 className={styles.sideTitle}>Присоединяйся</h1>
          <p className={styles.sideCopy}>
            Аккаунт нужен, чтобы сохранять подборки и — для админов — вести каталог.
          </p>
        </div>
      </aside>

      <main className={styles.formPane}>
        <form className={styles.card} onSubmit={handleSubmit} noValidate>
          <h2 className={styles.cardTitle}>Регистрация</h2>

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
            {fieldErrors.username && (
              <p className="field-error">{fieldErrors.username}</p>
            )}
          </div>

          <div className="field">
            <label htmlFor="email">Email</label>
            <input
              id="email"
              name="email"
              type="email"
              autoComplete="email"
              value={form.email}
              onChange={update('email')}
              required
            />
            {fieldErrors.email && (
              <p className="field-error">{fieldErrors.email}</p>
            )}
          </div>

          <div className="field">
            <label htmlFor="password">Пароль</label>
            <input
              id="password"
              name="password"
              type="password"
              autoComplete="new-password"
              minLength={8}
              value={form.password}
              onChange={update('password')}
              required
            />
            {fieldErrors.password && (
              <p className="field-error">{fieldErrors.password}</p>
            )}
          </div>

          {formError && <p className="field-error">{formError}</p>}

          <button
            type="submit"
            className="btn btn-primary"
            disabled={submitting}
          >
            {submitting ? 'Создаём аккаунт…' : 'Зарегистрироваться'}
          </button>

          <p className={styles.switch}>
            Уже есть аккаунт? <Link to="/login">Войти</Link>
          </p>
        </form>
      </main>
    </div>
  );
}