
import { getAuthToken } from '../util/auth';

export function useVote() {
  const vote = async (movieId, voteType) => {
    const token = getAuthToken();

    if (!token) {
      alert('You must be logged in to vote.');
      return;
    }

    try {
      const response = await fetch(`http://localhost:8000/api/movies/${movieId}/vote`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({ vote_type: voteType }),
      });

      if (!response.ok) {
        const errorData = await response.json();
        throw new Error(errorData.message || 'Voting failed.');
      }

      const result = await response.json();
      return result;
    } catch (error) {
      
      alert(error.message);
    }
  };

  return vote;
}
