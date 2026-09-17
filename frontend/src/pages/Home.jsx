import { useCallback, useEffect, useState } from 'react';

import { fetchItems } from '../api/items';
import { ApiError } from '../api/client';

import TrackRow from '../components/TrackRow';
import Loading from '../components/Loading';
import ErrorMessage from '../components/ErrorMessage';

import styles from './Home.module.css';

const PER_PAGE = 20;

export default function Home() {
  const [searchInput, setSearchInput] = useState('');
  const [search, setSearch] = useState('');
  const [page, setPage] = useState(0);
  const [items, setItems] = useState([]);
  const [pageCount, setPageCount] = useState(1);

  const [status, setStatus] = useState('loading'); // loading | ready | error
  const [error, setError] = useState(null);

  const load = useCallback(async () => {
    setStatus('loading');
    setError(null);

    try {
      const data = await fetchItems({
      page: page + 1,
      'per-page': PER_PAGE,
      name: search || undefined,
      expand: 'artist,genres',
    });

      const list = data?.items ?? [];
      const totalPages = data?._meta?.pageCount ?? 1;

      setItems(list);
      setPageCount(totalPages);
      setStatus('ready');
    } catch (err) {
      setError(
        err instanceof ApiError
          ? err.message
          : 'Не получилось загрузить каталог.'
      );
      setStatus('error');
    }
  }, [page, search]);

  useEffect(() => {
    load();
  }, [load]);

  function handleSearchSubmit(e) {
    e.preventDefault();
    setPage(0);
    setSearch(searchInput.trim());
  }

  const hasNextPage = page + 1 < pageCount;

  return (
    <div className={styles.page}>
      <section className={styles.hero}>
        <h1 className={styles.heroTitle}>Каталог треков</h1>

        <p className={styles.heroCopy}>
          Просматривай и ищи по библиотеке свои любимые треки.
        </p>
      </section>

      <form
        className={styles.searchBar}
        onSubmit={handleSearchSubmit}
        role="search"
      >
        <input
          type="search"
          placeholder="Название трека…"
          value={searchInput}
          onChange={(e) => setSearchInput(e.target.value)}
          aria-label="Поиск по названию"
        />

        <button type="submit" className="btn btn-primary">
          Найти
        </button>
      </form>

      {status === 'loading' && (
        <Loading label="Загружаем каталог…" />
      )}

      {status === 'error' && (
        <ErrorMessage message={error} onRetry={load} />
      )}

      {status === 'ready' && items.length === 0 && (
        <p className={styles.empty}>
          {search
            ? `По запросу «${search}» ничего не нашлось.`
            : 'В каталоге пока нет треков.'}
        </p>
      )}

      {status === 'ready' && items.length > 0 && (
        <>
          <ul className={styles.list}>
            {items.map((item, i) => (
              <TrackRow
                key={item.id}
                index={page * PER_PAGE + i + 1}
                item={item}
              />
            ))}
          </ul>

          <div className={styles.pagination}>
            <button
              type="button"
              className="btn btn-ghost"
              onClick={() => setPage((p) => Math.max(0, p - 1))}
              disabled={page === 0}
            >
              Назад
            </button>

            <span className={styles.pageLabel}>
              Страница {page + 1} из {pageCount}
            </span>

            <button
              type="button"
              className="btn btn-ghost"
              onClick={() => setPage((p) => p + 1)}
              disabled={!hasNextPage}
            >
              Дальше
            </button>
          </div>
        </>
      )}
    </div>
  );
}
