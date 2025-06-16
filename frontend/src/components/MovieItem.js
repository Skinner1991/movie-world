import { Link } from 'react-router-dom';
import classes from './MoviesList.module.css';
import MovieVote from './MovieVote';

function MovieItem({ movie, currentUserId, currentVote, onVote, isVoting }) {
  return (
    <li key={movie.id} className={classes.item}>
      <div className={classes.header}>
        <h2>{movie.title}</h2>
        <span className={classes.date}>
          Posted {new Date(movie.published_at).toLocaleDateString()}
        </span>
      </div>

      <p className={classes.content}>{movie.description}</p>

      <div className={classes.footer}>
        <span>
          <MovieVote
            movie={movie}
            currentUserId={currentUserId}
            currentVote={currentVote}
            onVote={onVote}
            isLoading={isVoting}
          />
        </span>
        <span>
          Posted by{' '}
          <Link
            to={`/movies?user_id=${movie.user_id}`}
            className={classes.userLink}
          >
            {movie.user_name}
          </Link>
        </span>
      </div>
    </li>
  );
}

export default MovieItem;
