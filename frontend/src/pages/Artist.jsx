import { useEffect, useState } from 'react';
import { Link, useParams } from 'react-router-dom';

import { fetchArtist } from '../api/artists';
import { ApiError } from '../api/client';

import Loading from '../components/Loading';
import ErrorMessage from '../components/ErrorMessage';

import styles from './Artist.module.css';

export default function Artist() {
  const { id } = useParams();

  const [artist, setArtist] = useState(null);
  const [status, setStatus] = useState('loading');
  const [error, setError] = useState(null);

  useEffect(() => {
    setStatus('loading');
    setError(null);

    fetchArtist(id, {
      expand: 'albums',
    })
      .then((data) => {
        setArtist(data);
        setStatus('ready');
      })
      .catch((err) => {
        setError(
          err instanceof ApiError
            ? err.message
            : 'Не получилось загрузить исполнителя.'
        );
        setStatus('error');
      });
  }, [id]);

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