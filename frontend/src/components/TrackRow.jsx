import styles from './TrackRow.module.css';

function formatDuration(seconds) {
  if (!seconds && seconds !== 0) return null;
  const m = Math.floor(seconds / 60);
  const s = Math.floor(seconds % 60)
    .toString()
    .padStart(2, '0');
  return `${m}:${s}`;
}

export default function TrackRow({ index, item }) {
  const artistName = item.artist?.name ?? (item.artist_id ? `Артист #${item.artist_id}` : 'Артист неизвестен');
  const genres = Array.isArray(item.genres) ? item.genres : [];
  const duration = formatDuration(item.duration);

  return (
    <li className={styles.row}>
      <span className={styles.index}>{String(index).padStart(2, '0')}</span>
      <div className={styles.main}>
        <p className={styles.name}>{item.name}</p>
        <div className={styles.meta}>
          <span className={styles.artist}>{artistName}</span>
          {genres.map((genre) => (
            <span key={genre.id ?? genre.name} className={styles.tag}>
              {genre.name}
            </span>
          ))}
        </div>
      </div>
      <img
        src={item.image_url}
        alt=""
        width="80"
        height="80"
      />
      {item.status && <span className={styles.status}>{item.status}</span>}
      {duration && <span className={styles.duration}>{duration}</span>}
    </li>
  );
}
