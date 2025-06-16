import { useState } from 'react';
import { getUserId } from '../util/auth';
import { useVote } from '../hooks/useVote';
import MovieItem from './MovieItem';
import MovieSort from './MovieSort';
import classes from './MoviesList.module.css';

function MoviesList({ movies, count }) {
  const currentUserId = getUserId();
  const vote = useVote();

  const [loadingMovieId, setLoadingMovieId] = useState(null);
  const [movieVotes, setMovieVotes] = useState(() =>
    movies.map(movie => ({ ...movie, currentVote: null }))
  );

  const handleVote = async (movieId, voteType) => {
    setLoadingMovieId(movieId);
    const result = await vote(movieId, voteType);
    setLoadingMovieId(null);

    if (!result) return;

    setMovieVotes(prev =>
      prev.map(movie =>
        movie.id !== movieId
          ? movie
          : {
              ...movie,
              likes: result.likes,
              hates: result.hates,
              currentVote: result.current_vote,
            }
      )
    );
  };

  return (
    <div className={classes.events}>
      <div className={classes.headerRow}>
        <p>Found {count} movies</p>
        <MovieSort />
      </div>
      <ul className={classes.list}>
        {movieVotes.map(movie => (
          <MovieItem
            key={movie.id}
            movie={movie}
            currentUserId={currentUserId}
            currentVote={movie.currentVote}
            onVote={handleVote}
            isVoting={loadingMovieId === movie.id}
          />
        ))}
      </ul>
    </div>
  );
}

export default MoviesList;
