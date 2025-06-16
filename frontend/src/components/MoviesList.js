import { useState } from 'react';
import { useNavigation } from 'react-router-dom';
import { getUserId } from '../util/auth';
import { useVote } from '../hooks/useVote';
import MovieItem from './MovieItem';
import MovieSort from './MovieSort';
import classes from './MoviesList.module.css';

import { LoaderOverlay } from './LoaderOverlay';


function MoviesList({ movies, count }) {
  const currentUserId = getUserId();
  const vote = useVote();

  const [loadingMovieId, setLoadingMovieId] = useState(null);
  const [movieVotes, setMovieVotes] = useState(() =>
    movies.map(movie => ({ ...movie, currentVote: null }))
  );

  const navigation = useNavigation();
  const isLoading = navigation.state === 'loading';

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
      <div className={classes.listContainer}>
        {isLoading && <LoaderOverlay />}
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
    </div>
  );
}

export default MoviesList;
