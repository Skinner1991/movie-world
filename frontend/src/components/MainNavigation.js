import { Form, NavLink, useRouteLoaderData, useLocation } from 'react-router-dom';

import classes from './MainNavigation.module.css';
import { getAuthToken, getUserId } from '../util/auth';

function MainNavigation() {

  const token = useRouteLoaderData('root');
  const currentUserId = getUserId();
  const location = useLocation();
  const isOnMoviesPage = location.pathname === '/movies';

  return (
    <header className={classes.header}>
      <nav>
        <ul className={classes.list}>
         
          <li>
            <NavLink
              to="/movies"
              className={({ isActive }) =>
                isActive ? classes.active : undefined
              }
            >
              Movie World
            </NavLink>
          </li>

          {
            token && isOnMoviesPage && (
              <li>
                <NavLink
                  to="/movies/new"
                  className={({ isActive }) =>
                    isActive ? classes.active : undefined
                  }
                >
                  New Movie
                </NavLink>
              </li>
            )
          }

          {
            !token &&
            (<li>
              <NavLink
                to="/auth?mode=login"
                className={({ isActive }) =>
                  isActive ? classes.active : undefined
                }
              >
                Authentication
              </NavLink>
            </li>)
          }
          {
            token && 
            (<li>
              <Form action="/logout" method="post">
                <button >Logout {currentUserId}</button>
              </Form>
            </li>
          )}
          
        </ul>
      </nav>
    </header>
  );
}

export default MainNavigation;
