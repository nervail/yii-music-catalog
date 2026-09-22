import { useEffect, useState } from 'react';
import { Link, useParams } from 'react-router-dom';

import { fetchItem } from '../api/items';
import { ApiError } from '../api/client';

import Loading from '../components/Loading';
import ErrorMessage from '../components/ErrorMessage';

import styles from './Track.module.css';

export default function Track() {
  const { id } = useParams();

  const [track, setTrack] = useState(null);
  const [status, setStatus] = useState('loading');
  const [error, setError] = useState(null);

  useEffect(() => {
    setStatus('loading');
    setError(null);

    fetchItem(id, {
      expand: 'artist,album,genres',
    })
      .then((data) => {
        setTrack(data);
        setStatus('ready');
      })
      .catch((err) => {
        setError(
          err instanceof ApiError
            ? err.message
            : 'Не получилось загрузить трек.'
        );
        setStatus('error');
      });
  }, [id]);

  return (
    <div className={styles.page}>
      <main className={styles.content}>
        {status === 'loading' && (
          <Loading label="Загружаем трек…" />
        )}

        {status === 'error' && (
          <ErrorMessage message={error} />
        )}

        {status === 'ready' && track && (
          <>
            <div className={styles.breadcrumbs}>
              <Link to="/">Каталог</Link>
              <span>/</span>
              <span>{track.name}</span>
            </div>

            <section className={styles.track}>
              <div className={styles.cover}>
                <img
                  src={track.image_url}
                  alt={`Обложка трека ${track.name}`}
                />
              </div>

              <div className={styles.info}>
                <p className={styles.label}>Трек</p>

                <h1 className={styles.title}>
                  {track.name}
                </h1>

                <div className={styles.meta}>
                  <div className={styles.metaItem}>
                    <span className={styles.metaLabel}>
                      Исполнитель
                    </span>

                    <Link
                      to={`/artists/${track.artist.id}`}
                      className={styles.link}
                    >
                      {track.artist.name}
                    </Link>
                  </div>

                  <div className={styles.metaItem}>
                    <span className={styles.metaLabel}>
                      Альбом
                    </span>

                    <Link
                      to={`/albums/${track.album.id}`}
                      className={styles.link}
                    >
                      {track.album.name}
                    </Link>
                  </div>
                </div>

                {track.genres?.length > 0 && (
                  <div className={styles.genres}>
                    {track.genres.map((genre) => (
                      <span
                        key={genre.id}
                        className={styles.genre}
                      >
                        {genre.name}
                      </span>
                    ))}
                  </div>
                )}
              </div>
            </section>

            {track.description && (
              <section className={styles.description}>
                <h2>Описание</h2>

                <p>{track.description}</p>
              </section>
            )}
          </>
        )}
      </main>
    </div>
  );
}