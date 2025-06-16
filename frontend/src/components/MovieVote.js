import classes from './MoviesList.module.css';
import { FaThumbsUp, FaThumbsDown } from 'react-icons/fa';

function MovieVote({ movie, currentUserId, currentVote, onVote, isLoading }) {
  const isOwnMovie = String(movie.user_id) === String(currentUserId);

  if (isOwnMovie || currentUserId === null) {
    return <> 
        <FaThumbsUp color={'blue'} /> {movie.likes}
        {' | '}
        <FaThumbsDown color={'red'} /> {movie.hates}
       </>;
  }

  if (isLoading) {
    return <span className={classes.loading}>Voting...</span>;
  }

  return (
    <>
      <span
        className={classes.iconVote}
        onClick={() => onVote(movie.id, 'like')}
      >
        <FaThumbsUp color={'blue'} />
       {movie.likes} {currentVote === 'like' && '(voted)'}
      </span>
      {' | '}
      <span
        className={classes.iconVote}
        onClick={() => onVote(movie.id, 'hate')}
      >
        <FaThumbsDown color={'red'} /> 
        {movie.hates} {currentVote === 'hate' && '(voted)'}
      </span>
    </>
  );
}

export default MovieVote;
