import { useCallback, useEffect, useState } from 'react';
import { Link } from 'react-router-dom';

import { fetchArtists } from '../api/artists';
import { ApiError } from '../api/client';

import Loading from '../components/Loading';
import ErrorMessage from '../components/ErrorMessage';

import styles from './Artists.module.css';

const PER_PAGE = 20;

export default function Artists() {
  const [searchInput, setSearchInput] = useState('');
  const [search, setSearch] = useState('');

  const [artists, setArtists] = useState([]);
  const [page, setPage] = useState(1);
  const [pageCount, setPageCount] = useState(1);

  const [status, setStatus] = useState('loading');
  const [error, setError] = useState(null);

  const load = useCallback(async () => {
    setStatus('loading');
    setError(null);

    try {
      const data = await fetchArtists({
        page,
        'per-page': PER_PAGE,
        name: search || undefined,
      });

      setArtists(data?.artists ?? []);
      setPageCount(data?._meta?.pageCount ?? 1);
      setStatus('ready');
    } catch (err) {
      setError(
        err instanceof ApiError
          ? err.message
          : 'Не получилось загрузить исполнителей.'
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
            <h1 className={styles.title}>Исполнители</h1>
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
              placeholder="Имя исполнителя…"
              value={searchInput}
              onChange={(e) => setSearchInput(e.target.value)}
              aria-label="Поиск по имени исполнителя"
            />

            <button type="submit" className="btn btn-primary">
              Найти
            </button>
          </form>

          {status === 'loading' && (
            <Loading label="Загружаем исполнителей…" />
          )}

          {status === 'error' && (
            <ErrorMessage message={error} onRetry={load} />
          )}

          {status === 'ready' && (
            <>
              {artists.length === 0 ? (
                <p className={styles.empty}>
                  {search
                    ? `По запросу «${search}» ничего не нашлось.`
                    : 'Исполнителей пока нет.'}
                </p>
              ) : (
                <div className={styles.list}>
                  {artists.map((artist) => (
                    <Link
                      key={artist.id}
                      to={`/artists/${artist.id}`}
                      className={styles.artist}
                    >
                      <span className={styles.name}>
                        {artist.name}
                      </span>
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