import { useCallback, useEffect, useState } from 'react';
import { Link } from 'react-router-dom';

import { fetchSubscriptions, unsubscribe } from '../api/subscriptions';
import { ApiError } from '../api/client';

import Loading from '../components/Loading';
import ErrorMessage from '../components/ErrorMessage';

import styles from './Subscriptions.module.css';

export default function Subscriptions() {
  const [subscriptions, setSubscriptions] = useState([]);
  const [status, setStatus] = useState('loading');
  const [error, setError] = useState(null);
  const [unsubscribingId, setUnsubscribingId] = useState(null);

  const load = useCallback(async () => {
    setStatus('loading');
    setError(null);

    try {
      const data = await fetchSubscriptions({
        expand: 'artist',
      });

      setSubscriptions(data?.subscriptions ?? []);
      setStatus('ready');
    } catch (err) {
      setError(
        err instanceof ApiError
          ? err.message
          : 'Не получилось загрузить подписки.'
      );
      setStatus('error');
    }
  }, []);

  useEffect(() => {
    load();
  }, [load]);

  async function handleUnsubscribe(artistId) {
    setUnsubscribingId(artistId);

    try {
      await unsubscribe(artistId);

      setSubscriptions((current) =>
        current.filter(
          (subscription) => subscription.artist_id !== artistId
        )
      );
    } catch (err) {
      setError(
        err instanceof ApiError
          ? err.message
          : 'Не получилось отменить подписку.'
      );
    } finally {
      setUnsubscribingId(null);
    }
  }

  return (
    <div className={styles.page}>
      <main className={styles.pageContent}>
        <div className={styles.header}>
          <div>
            <p className={styles.label}>Аккаунт</p>
            <h1 className={styles.title}>Мои подписки</h1>
          </div>
        </div>

        <div className={styles.content}>
          {status === 'loading' && (
            <Loading label="Загружаем подписки…" />
          )}

          {status === 'error' && (
            <ErrorMessage message={error} onRetry={load} />
          )}

          {status === 'ready' && (
            <>
              {subscriptions.length === 0 ? (
                <p className={styles.empty}>
                  Вы пока ни на кого не подписаны.
                </p>
              ) : (
                <div className={styles.list}>
                  {subscriptions.map((subscription) => {
                    const artist = subscription.artist;

                    if (!artist) {
                      return null;
                    }

                    const isUnsubscribing =
                      unsubscribingId === artist.id;

                    return (
                      <div
                        key={subscription.id}
                        className={styles.item}
                      >
                        <Link
                          to={`/artists/${artist.id}`}
                          className={styles.artist}
                        >
                          {artist.name}
                        </Link>

                        <button
                          type="button"
                          className="btn btn-ghost"
                          onClick={() =>
                            handleUnsubscribe(artist.id)
                          }
                          disabled={isUnsubscribing}
                        >
                          {isUnsubscribing
                            ? 'Отписываемся…'
                            : 'Отписаться'}
                        </button>
                      </div>
                    );
                  })}
                </div>
              )}

              {error && <ErrorMessage message={error} />}
            </>
          )}
        </div>
      </main>
    </div>
  );
}