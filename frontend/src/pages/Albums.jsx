import { useCallback, useEffect, useState } from 'react';
import { Link } from 'react-router-dom';

import { fetchAlbums } from '../api/albums';
import { ApiError } from '../api/client';

import Loading from '../components/Loading';
import ErrorMessage from '../components/ErrorMessage';

import styles from './Albums.module.css';

const PER_PAGE = 20;

export default function Albums() {
  const [searchInput, setSearchInput] = useState('');
  const [search, setSearch] = useState('');

  const [albums, setAlbums] = useState([]);
  const [page, setPage] = useState(1);
  const [pageCount, setPageCount] = useState(1);

  const [status, setStatus] = useState('loading');
  const [error, setError] = useState(null);

  const load = useCallback(async () => {
    setStatus('loading');
    setError(null);

    try {
      const data = await fetchAlbums({
        page,
        'per-page': PER_PAGE,
        name: search || undefined,
        expand: 'artist',
      });

      setAlbums(data?.albums ?? []);
      setPageCount(data?._meta?.pageCount ?? 1);
      setStatus('ready');
    } catch (err) {
      setError(
        err instanceof ApiError
          ? err.message
          : 'Не получилось загрузить альбомы.'
      );
      setStatus('error');
    }
  }, [page, search]);

  useEffect(() => {
    load();
  }, [load]);

  function handleSearchSubmit(e) {
    e.preventDefault();

    setPage(1);
    setSearch(searchInput.trim());
  }

  return (
    <div className={styles.page}>
      <main className={styles.pageContent}>
        <div className={styles.header}>
          <div>
            <p className={styles.label}>Каталог</p>
            <h1 className={styles.title}>Альбомы</h1>
          </div>
        </div>
        
        <div className={styles.content}>

            <form
            className={styles.searchBar}
            onSubmit={handleSearchSubmit}
            role="search"
            >
            <input
                type="search"
                placeholder="Название альбома…"
                value={searchInput}
                onChange={(e) => setSearchInput(e.target.value)}
                aria-label="Поиск по названию альбома"
            />

            <button type="submit" className="btn btn-primary">
                Найти
            </button>
            </form>

            {status === 'loading' && (
            <Loading label="Загружаем альбомы…" />
            )}

            {status === 'error' && (
            <ErrorMessage message={error} onRetry={load} />
            )}

            {status === 'ready' && (
            <>
                {albums.length === 0 ? (
                <p className={styles.empty}>
                    {search
                    ? `По запросу «${search}» ничего не нашлось.`
                    : 'Альбомов пока нет.'}
                </p>
                ) : (
                <div className={styles.list}>
                    {albums.map((album) => (
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

                        <div className={styles.info}>
                        <h2 className={styles.name}>
                            {album.name}
                        </h2>

                        {album.artist && (
                            <p className={styles.artist}>
                            {album.artist.name}
                            </p>
                        )}
                        </div>
                    </Link>
                    ))}
                </div>
                )}

                {pageCount > 1 && (
                <div className={styles.pagination}>
                    <button
                    type="button"
                    className="btn btn-ghost"
                    onClick={() => setPage((value) => value - 1)}
                    disabled={page <= 1}
                    >
                    Назад
                    </button>

                    <span>
                    Страница {page} из {pageCount}
                    </span>

                    <button
                    type="button"
                    className="btn btn-ghost"
                    onClick={() => setPage((value) => value + 1)}
                    disabled={page >= pageCount}
                    >
                    Дальше
                    </button>
                </div>
                )}
            </>
            )}
        </div>
      </main>
    </div>
  );
}