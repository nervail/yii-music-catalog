import { useState } from 'react';
import { Link } from 'react-router-dom';
import { ApiError } from '../api/client';
import styles from './AuthLayout.module.css';
import { requestPasswordReset } from '../api/auth';

export default function ForgotPassword() {
  const [email, setEmail] = useState('');
  const [submitting, setSubmitting] = useState(false);
  const [formError, setFormError] = useState(null);
  const [sent, setSent] = useState(false);

  async function handleSubmit(e) {
    e.preventDefault();

    setFormError(null);
    setSubmitting(true);

    try {
        await requestPasswordReset(email);
        setSent(true);
    } catch (err) {
        setFormError(
        err instanceof ApiError
          ? err.message
          : 'Не получилось отправить запрос. Попробуй ещё раз.'
      );
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
          <h1 className={styles.sideTitle}>Восстановление доступа</h1>
          <p className={styles.sideCopy}>
            Введи email, который использовал при регистрации.
          </p>
        </div>
      </aside>

      <main className={styles.formPane}>
        <form className={styles.card} onSubmit={handleSubmit} noValidate>
          <h2 className={styles.cardTitle}>Забыли пароль?</h2>

          {sent ? (
            <>
              <p className={styles.confirmText}>
                Если аккаунт с таким email существует, мы отправили
                инструкцию по восстановлению пароля.
              </p>

              <p className={styles.switch}>
                <Link to="/login">Вернуться ко входу</Link>
              </p>
            </>
          ) : (
            <>
              <div className="field">
                <label htmlFor="email">Email</label>
                <input
                  id="email"
                  name="email"
                  type="email"
                  autoComplete="email"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  required
                />
              </div>

              {formError && (
                <p className="field-error">{formError}</p>
              )}

              <button
                type="submit"
                className="btn btn-primary"
                disabled={submitting}
              >
                {submitting ? 'Отправляем…' : 'Отправить'}
              </button>

              <p className={styles.switch}>
                Вспомнил пароль? <Link to="/login">Войти</Link>
              </p>
            </>
          )}
        </form>
      </main>
    </div>
  );
}