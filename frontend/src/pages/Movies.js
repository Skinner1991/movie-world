import { Suspense } from 'react';
import { useLoaderData, defer, Await, useLocation } from 'react-router-dom';

import MoviesList from '../components/MoviesList';

function MoviesPage() {
  const { movies, count } = useLoaderData();
  const location = useLocation();
  return (
    <>
      <Suspense fallback={<p style={{ textAlign: 'center' }}>Loading Movies...</p>}>
        <Await resolve={movies}>
          {
            (loadedEvents) => (<MoviesList key={location.search} movies={loadedEvents} count={count} />)
          }
        </Await>
      </Suspense>

    </>
  );
}

export default MoviesPage;

async function loadEvents({ request }) {

  const url = new URL(request.url)
  const sort = url.searchParams.get('orderby');
  const userId = url.searchParams.get('user_id');

  let apiUrl = 'http://localhost:8000/api/movies';

  const params = new URLSearchParams();
  if (sort) params.append('orderby', sort);
  if (userId) params.append('user_id', userId);

  if ([...params].length > 0) {
    apiUrl += `?${params.toString()}`;
  }

  const response = await fetch(apiUrl);

  if (!response.ok) {
    
    throw new Response(JSON.stringify({ message: 'Could not fetch movies.' }), {
      status: 500,
    });
  } else {
    const resData = await response.json();
    return resData;
  }
}

export function loader({ request }) {
  const dataPromise = loadEvents({ request });

  return defer({
    movies: dataPromise.then(data => data.result),
    count: dataPromise.then(data => data.count),
  });
}
