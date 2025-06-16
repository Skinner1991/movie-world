import {  redirect } from 'react-router-dom';
import AuthForm from '../components/AuthForm';

function AuthenticationPage() {
  return (
    <>
      <AuthForm />
    </>
  );;
}

export default AuthenticationPage;

export async function action({request}) {
  const searchParams = new URL(request.url).searchParams;
  const mode = searchParams.get('mode') || 'login';

  if( mode !== 'login' && mode !== 'signup' ) {
    return {
      errors: null,
      message: 'Unsupported mode.',
    };
  }

  const data = await request.formData();
  let authData = {
    email: data.get('email'),
    password: data.get('password')
  }

  if(mode === 'signup') {
    authData.name = data.get('name')
  }

  const response = await fetch('http://localhost:8000/api/'+mode, {
    method: 'POST',
    headers: {
      'Content-type': 'application/json'
    },
    body: JSON.stringify(authData)
  });

  if (response.status === 400 || response.status === 401) {
    const errorData = await response.json();
    return {
      message: errorData.message || 'Invalid credentials.'
    };
  }

  if (!response.ok) {
    throw new Response(JSON.stringify({ message: 'Could not authenticate user.' }), {
      status: 500,
    });
  }

  // manage token
  const resData = await response.json();
  const token = resData.token;
  const userId = resData.userId;

  localStorage.setItem('token', token);
  localStorage.setItem('userId', userId);
  const expiration = new Date();
  expiration.setHours(expiration.getHours() + 1);
  localStorage.setItem('expiration', expiration.toISOString());
  
  return redirect('/movies');
}