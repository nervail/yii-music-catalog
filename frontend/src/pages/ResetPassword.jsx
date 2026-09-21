import { useEffect, useState } from 'react';
import { Link, useNavigate, useSearchParams } from 'react-router-dom';
import { checkResetToken, resetPassword } from '../api/auth';
import { ApiError } from '../api/client';
import styles from './AuthLayout.module.css';

export default function ResetPassword() {
  const [searchParams] = useSearchParams();
  const navigate = useNavigate();

  const token = searchParams.get('token');

  const [checkingToken, setCheckingToken] = useState(true);
  const [tokenValid, setTokenValid] = useState(false);

  useEffect(() => {
    async function checkToken() {
      if (!token) {
        setTokenValid(false);
        setCheckingToken(false);
        return;
      }

      try {
        await checkResetToken(token);
        setTokenValid(true);
      } catch {
        setTokenValid(false);
      } finally {
        setCheckingToken(false);
      }
    }

    checkToken();
  }, [token]);

  const [form, setForm] = useState({
    password: '',
    passwordConfirm: '',
  });

  const [submitting, setSubmitting] = useState(false);
  const [formError, setFormError] = useState(null);
  const [success, setSuccess] = useState(false);

  function update(field) {
    return (e) => {
      setForm((prev) => ({
        ...prev,
        [field]: e.target.value,
      }));
    };
  }

  async function handleSubmit(e) {
    e.preventDefault();

    setFormError(null);

    if (!token) {
      setFormError('Ссылка для восстановления пароля недействительна.');
      return;
    }

    if (form.password !== form.passwordConfirm) {
      setFormError('Пароли не совпадают.');
      return;
    }

    setSubmitting(true);

    try {
      await resetPassword(token, form.password);
      setSuccess(true);
    } catch (err) {
      setFormError(
        err instanceof ApiError
          ? err.message
          : 'Не получилось изменить пароль. Попробуй ещё раз.'
      );
    } finally {
      setSubmitting(false);
    }
  }

  if (checkingToken) {
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
              Проверяем ссылку для сброса пароля.
            </p>
          </div>
        </aside>

        <main className={styles.formPane}>
          <div className={styles.card}>
            <h2 className={styles.cardTitle}>Проверяем ссылку…</h2>
          </div>
        </main>
      </div>
    );
  }

  if (!tokenValid) {
    return (
      <div className={styles.screen}>
        <aside className={styles.side}>
          <div className={styles.grooves} aria-hidden="true">
            <span />
            <span />
            <span />
          </div>

          <div className={styles.sideText}>
            <h1 className={styles.sideTitle}>Ссылка недействительна</h1>
            <p className={styles.sideCopy}>
              Ссылка истекла или уже была использована.
            </p>
          </div>
        </aside>

        <main className={styles.formPane}>
          <div className={styles.card}>
            <h2 className={styles.cardTitle}>Сброс пароля</h2>

            <p className={styles.confirmText}>
              Запроси новое письмо для восстановления пароля.
            </p>

            <Link to="/forgot-password" className="btn btn-primary">
              Запросить новую ссылку
            </Link>
          </div>
        </main>
      </div>
    );
  }

  if (success) {
    return (
      <div className={styles.screen}>
        <aside className={styles.side}>
          <div className={styles.grooves} aria-hidden="true">
            <span />
            <span />
            <span />
          </div>

          <div className={styles.sideText}>
            <h1 className={styles.sideTitle}>Пароль изменён</h1>
            <p className={styles.sideCopy}>
              Теперь можешь войти с новым паролем.
            </p>
          </div>
        </aside>

        <main className={styles.formPane}>
          <div className={styles.card}>
            <h2 className={styles.cardTitle}>Готово</h2>

            <p className={styles.confirmText}>
              Новый пароль сохранён.
            </p>

            <button
              type="button"
              className="btn btn-primary"
              onClick={() => navigate('/login')}
            >
              Войти
            </button>
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
          <h1 className={styles.sideTitle}>Новый пароль</h1>
          <p className={styles.sideCopy}>
            Придумай новый пароль для своего аккаунта.
          </p>
        </div>
      </aside>

      <main className={styles.formPane}>
        <form
          className={styles.card}
          onSubmit={handleSubmit}
          noValidate
        >
          <h2 className={styles.cardTitle}>Сброс пароля</h2>

          <div className="field">
            <label htmlFor="password">Новый пароль</label>
            <input
              id="password"
              name="password"
              type="password"
              autoComplete="new-password"
              value={form.password}
              onChange={update('password')}
              required
            />
          </div>

          <div className="field">
            <label htmlFor="passwordConfirm">
              Повтори пароль
            </label>
            <input
              id="passwordConfirm"
              name="passwordConfirm"
              type="password"
              autoComplete="new-password"
              value={form.passwordConfirm}
              onChange={update('passwordConfirm')}
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
            {submitting ? 'Сохраняем…' : 'Сохранить пароль'}
          </button>

          <p className={styles.switch}>
            <Link to="/login">Вернуться ко входу</Link>
          </p>
        </form>
      </main>
    </div>
  );
}
