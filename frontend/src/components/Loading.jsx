import styles from './Loading.module.css';

export default function Loading({ label = 'Загрузка…' }) {
  return (
    <div className={styles.wrap} role="status" aria-live="polite">
      <span className={styles.groove} aria-hidden="true" />
      <span>{label}</span>
    </div>
  );
}
