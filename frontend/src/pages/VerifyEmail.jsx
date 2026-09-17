import { useEffect, useState } from 'react';
import { Link, useSearchParams } from 'react-router-dom';

import { verifyEmail } from '../api/auth';
import styles from './AuthLayout.module.css';

export default function VerifyEmail() {
  const [searchParams] = useSearchParams();
  const token = searchParams.get('token');

  const [status, setStatus] = useState('loading');
  const [message, setMessage] = useState('');

  useEffect(() => {
    if (!token) {
      setStatus('error');
      setMessage('Токен подтверждения отсутствует.');
      return;
    }

    verifyEmail(token)
      .then((data) => {
        setStatus('success');
        setMessage(data?.message ?? 'Email успешно подтверждён.');
      })
      .catch((error) => {
        setStatus('error');
        setMessage(error?.message ?? 'Не удалось подтвердить email.');
      });
  }, [token]);

  return (
    <div className={styles.screen}>
      <aside className={styles.side}>
        <div className={styles.grooves} aria-hidden="true">
          <span />
          <span />
          <span />
        </div>

        <div className={styles.sideText}>
          <h1 className={styles.sideTitle}>Music Catalog</h1>
          <p className={styles.sideCopy}>
            Подтверди свой email, чтобы получить доступ к аккаунту.
          </p>
        </div>
      </aside>

      <main className={styles.formPane}>
        <div className={styles.card}>
          {status === 'loading' && (
            <>
              <h2 className={styles.cardTitle}>
                Подтверждение email
              </h2>

              <p className={styles.confirmText}>
                Проверяем ссылку подтверждения...
              </p>
            </>
          )}

          {status === 'success' && (
            <>
              <h2 className={styles.cardTitle}>
                Email подтверждён
              </h2>

              <p className={styles.confirmText}>
                {message}
              </p>

              <Link to="/login" className="btn btn-primary">
                Войти
              </Link>
            </>
          )}

          {status === 'error' && (
            <>
              <h2 className={styles.cardTitle}>
                Не удалось подтвердить email
              </h2>

              <p className={styles.confirmText}>
                {message}
              </p>

              <Link to="/login" className="btn btn-primary">
                Перейти ко входу
              </Link>
            </>
          )}
        </div>
      </main>
    </div>
  );
}