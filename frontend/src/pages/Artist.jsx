import { useEffect, useState } from 'react';
import { Link, useParams } from 'react-router-dom';

import { fetchArtist } from '../api/artists';
import {
  fetchSubscriptions,
  subscribe,
  unsubscribe,
} from '../api/subscriptions';
import { ApiError } from '../api/client';
import { useAuth } from '../context/AuthContext';

import Loading from '../components/Loading';
import ErrorMessage from '../components/ErrorMessage';

import styles from './Artist.module.css';

export default function Artist() {
  const { id } = useParams();
  const { isAuthenticated } = useAuth();

  const [artist, setArtist] = useState(null);
  const [isSubscribed, setIsSubscribed] = useState(false);
  const [subscriptionStatus, setSubscriptionStatus] = useState('idle');

  const [status, setStatus] = useState('loading');
  const [error, setError] = useState(null);

  useEffect(() => {
    let cancelled = false;

    async function load() {
      setStatus('loading');
      setError(null);

      try {
        const artistPromise = fetchArtist(id, {
          expand: 'albums',
        });

        const subscriptionsPromise = isAuthenticated
          ? fetchSubscriptions()
          : Promise.resolve(null);

        const [artistData, subscriptionsData] = await Promise.all([
          artistPromise,
          subscriptionsPromise,
        ]);

        if (cancelled) {
          return;
        }

        setArtist(artistData);

        if (subscriptionsData) {
          const subscribed = (
            subscriptionsData.subscriptions ?? []
          ).some(
            (subscription) =>
              subscription.artist_id === artistData.id
          );

          setIsSubscribed(subscribed);
        } else {
          setIsSubscribed(false);
        }

        setStatus('ready');
      } catch (err) {
        if (cancelled) {
          return;
        }

        setError(
          err instanceof ApiError
            ? err.message
            : 'Не получилось загрузить исполнителя.'
        );
        setStatus('error');
      }
    }

    load();

    return () => {
      cancelled = true;
    };
  }, [id, isAuthenticated]);

  async function handleSubscription() {
    if (!artist || subscriptionStatus !== 'idle') {
      return;
    }

    setSubscriptionStatus('loading');
    setError(null);

    try {
      if (isSubscribed) {
        await unsubscribe(artist.id);
        setIsSubscribed(false);
      } else {
        await subscribe(artist.id);
        setIsSubscribed(true);
      }
    } catch (err) {
      setError(
        err instanceof ApiError
          ? err.message
          : 'Не получилось изменить подписку.'
      );
    } finally {
      setSubscriptionStatus('idle');
    }
  }

  return (
    <div className={styles.page}>
      <main className={styles.pageContent}>
        {status === 'loading' && (
          <Loading label="Загружаем исполнителя…" />
        )}

        {status === 'error' && (
          <ErrorMessage message={error} />
        )}

        {status === 'ready' && artist && (
          <>
            <div className={styles.header}>
              <div className={styles.breadcrumbs}>
                <Link to="/artists">Исполнители</Link>
                <span>/</span>
                <span>{artist.name}</span>
              </div>

              <p className={styles.label}>Исполнитель</p>

              <h1 className={styles.title}>
                {artist.name}
              </h1>

              {isAuthenticated && (
                <div className={styles.subscription}>
                  <button
                    type="button"
                    className={
                      isSubscribed
                        ? 'btn btn-ghost'
                        : 'btn btn-primary'
                    }
                    onClick={handleSubscription}
                    disabled={subscriptionStatus === 'loading'}
                  >
                    {subscriptionStatus === 'loading'
                      ? isSubscribed
                        ? 'Отписываемся…'
                        : 'Подписываемся…'
                      : isSubscribed
                        ? 'Отписаться'
                        : 'Подписаться'}
                  </button>

                  <p className={styles.subscriptionHint}>
                    При выходе нового альбома придёт
                    уведомление на почту.
                  </p>
                </div>
              )}

              {error && (
                <div className={styles.subscriptionError}>
                  <ErrorMessage message={error} />
                </div>
              )}
            </div>

            <div className={styles.content}>
              <section className={styles.albums}>
                <div className={styles.sectionHeader}>
                  <h2>Альбомы</h2>

                  <span>
                    {artist.albums?.length ?? 0}
                  </span>
                </div>

                {artist.albums?.length > 0 ? (
                  <div className={styles.list}>
                    {artist.albums.map((album) => (
                      <Link
                        key={album.id}
                        to={`/albums/${album.id}`}
                        className={styles.album}
                      >
                        <div className={styles.cover}>
                          <img
                            src={album.image_url}
                            alt={`Обложка альбома ${album.name}`}
                          />
                        </div>

                        <h3 className={styles.albumName}>
                          {album.name}
                        </h3>
                      </Link>
                    ))}
                  </div>
                ) : (
                  <p className={styles.empty}>
                    У этого исполнителя пока нет альбомов.
                  </p>
                )}
              </section>
            </div>
          </>
        )}
      </main>
    </div>
  );
}