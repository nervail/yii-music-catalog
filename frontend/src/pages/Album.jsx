import { useEffect, useState } from 'react';
import { Link, useParams } from 'react-router-dom';

import { fetchAlbum } from '../api/albums';
import { ApiError } from '../api/client';

import Loading from '../components/Loading';
import ErrorMessage from '../components/ErrorMessage';

import styles from './Album.module.css';

export default function Album() {
  const { id } = useParams();

  const [album, setAlbum] = useState(null);
  const [status, setStatus] = useState('loading');
  const [error, setError] = useState(null);

  useEffect(() => {
    setStatus('loading');
    setError(null);

    fetchAlbum(id, {
      expand: 'artist,items',
    })
      .then((data) => {
        setAlbum(data);
        setStatus('ready');
      })
      .catch((err) => {
        setError(
          err instanceof ApiError
            ? err.message
            : 'Не получилось загрузить альбом.'
        );
        setStatus('error');
      });
  }, [id]);

  return (
    <div className={styles.page}>
      <main className={styles.content}>
        {status === 'loading' && (
          <Loading label="Загружаем альбом…" />
        )}

        {status === 'error' && (
          <ErrorMessage message={error} />
        )}

        {status === 'ready' && album && (
          <>
            <div className={styles.breadcrumbs}>
              <Link to="/albums">Альбомы</Link>
              <span>/</span>
              <span>{album.name}</span>
            </div>

            <section className={styles.album}>
              <div className={styles.cover}>
                <img
                  src={album.image_url}
                  alt={`Обложка альбома ${album.name}`}
                />
              </div>

              <div className={styles.info}>
                <p className={styles.label}>Альбом</p>

                <h1 className={styles.title}>
                  {album.name}
                </h1>

                {album.artist && (
                  <Link
                    to={`/artists/${album.artist.id}`}
                    className={styles.artist}
                  >
                    {album.artist.name}
                  </Link>
                )}
              </div>
            </section>

            <section className={styles.tracks}>
              <div className={styles.sectionHeader}>
                <h2>Треки</h2>

                <span>
                  {album.items?.length ?? 0}
                </span>
              </div>

              {album.items?.length > 0 ? (
                <ol className={styles.trackList}>
                  {album.items.map((item, index) => (
                    <li key={item.id} className={styles.track}>
                      <span className={styles.index}>
                        {String(index + 1).padStart(2, '0')}
                      </span>

                      <div className={styles.trackMain}>
                        <Link
                          to={`/tracks/${item.id}`}
                          className={styles.trackName}
                        >
                          {item.name}
                        </Link>

                        {item.description && (
                          <p className={styles.description}>
                            {item.description}
                          </p>
                        )}
                      </div>
                    </li>
                  ))}
                </ol>
              ) : (
                <p className={styles.empty}>
                  В этом альбоме пока нет треков.
                </p>
              )}
            </section>
          </>
        )}
      </main>
    </div>
  );
}