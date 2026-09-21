import { useState } from 'react';
import { Link } from 'react-router-dom';
import { resendVerificationEmail } from '../api/auth';
import { ApiError } from '../api/client';
import styles from './AuthLayout.module.css';

export default function ResendVerification() {
  const [email, setEmail] = useState('');
  const [submitting, setSubmitting] = useState(false);
  const [formError, setFormError] = useState(null);
  const [sent, setSent] = useState(false);

  async function handleSubmit(e) {
    e.preventDefault();

    setFormError(null);
    setSubmitting(true);

    try {
      await resendVerificationEmail(email);
      setSent(true);
    } catch (err) {
      setFormError(
        err instanceof ApiError
          ? err.message
          : 'Не получилось отправить письмо. Попробуй ещё раз.'
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
          <h1 className={styles.sideTitle}>
            Подтверждение email
          </h1>

          <p className={styles.sideCopy}>
            Повторно отправим письмо для подтверждения твоего email.
          </p>
        </div>
      </aside>

      <main className={styles.formPane}>
        <form className={styles.card} onSubmit={handleSubmit} noValidate>
          <h2 className={styles.cardTitle}>
            Не пришло письмо?
          </h2>

          {sent ? (
            <>
              <p className={styles.confirmText}>
                Если для этого email требуется подтверждение,
                мы отправили новое письмо.
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
                {submitting ? 'Отправляем…' : 'Отправить письмо'}
              </button>

              <p className={styles.switch}>
                Вспомнил пароль?{' '}
                <Link to="/login">Войти</Link>
              </p>
            </>
          )}
        </form>
      </main>
    </div>
  );
}