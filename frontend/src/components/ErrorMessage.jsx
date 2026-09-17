import styles from './ErrorMessage.module.css';

export default function ErrorMessage({ message, onRetry }) {
  if (!message) return null;
  return (
    <div className={styles.wrap} role="alert">
      <p>{message}</p>
      {onRetry && (
        <button type="button" className="btn btn-ghost" onClick={onRetry}>
          Повторить
        </button>
      )}
    </div>
  );
}
