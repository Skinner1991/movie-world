import { RouterProvider, createBrowserRouter, redirect } from 'react-router-dom';

import ErrorPage from './pages/Error';

import MoviesPage, { loader as moviesLoader } from './pages/Movies';
import NewMoviePage from './pages/NewMovie';
import RootLayout from './pages/Root';
import { action as manipulateMovieAction } from './components/MovieForm';

import AuthenticationPage, {action as authAction} from './pages/Authentication';
import {action as logoutAction} from './pages/Logout';

import {tokenLoader, checkAuthLoader} from './util/auth';

const router = createBrowserRouter([
  {
    path: '/',
    element: <RootLayout />,
    errorElement: <ErrorPage />,
    id:'root',
    loader: tokenLoader,
    children: [
        {
          index: true,
          loader: () => redirect('/movies'),
        },
        {
          path: 'movies',
          element: <MoviesPage />,
          loader: moviesLoader,
        }, 
        {
          path: 'movies/new',
          element: <NewMoviePage />,
          action: manipulateMovieAction,
          loader: checkAuthLoader,
        },
        {
          path: 'auth',
          element: <AuthenticationPage />,
          action: authAction
        },
        {
          path: 'logout',
          action: logoutAction
        },
        {
          path: '*',
          element: <ErrorPage />,
        }
    ], 
  }
]);

function App() {
  return <RouterProvider router={router} />;
}

export default App;
