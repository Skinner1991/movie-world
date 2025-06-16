import MovieForm from '../components/MovieForm';
import MainNavigation from '../components/MainNavigation';

function NewMoviePage() {
  return (
    <>
      {/* <MainNavigation /> */}
      <MovieForm method="post" />
    </>
  );
}

export default NewMoviePage;

