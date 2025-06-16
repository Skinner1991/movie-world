import { Outlet, useLoaderData, useSubmit } from 'react-router-dom';

import MainNavigation from '../components/MainNavigation';
import { useEffect } from 'react';
import { getTokenDuration } from '../util/auth';

function RootLayout() {

  const token = useLoaderData();
  const submit = useSubmit();

  useEffect( ()=>{

    if(!token) {
      return;
    }

    if(token === 'EXPIRED') {
      submit(null, {action: '/logout', method: 'post'});
      return;
    }

    const duration = getTokenDuration();
    setTimeout( ()=>{
      submit(null, {action: '/logout', method: 'post'});
    }, duration )
  } ,[token, submit]);

  return (
    <>
      <MainNavigation />
      <main>
        <Outlet />
      </main>
    </>
  );
}

export default RootLayout;
