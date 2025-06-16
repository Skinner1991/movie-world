import { useNavigate, useSearchParams } from 'react-router-dom';
import classes from './MoviesList.module.css';

function MovieSort() {
  const [searchParams] = useSearchParams();
  const navigate = useNavigate();
  
  const currentSort = searchParams.get('orderby') || '';

  const handleSortChange = (e) => {
    const sort = e.target.value;
    const path = sort ? `/movies?orderby=${sort}` : '/movies';
    navigate(path);
  };

  return (
    <div className={classes.sortControls}>
      <label htmlFor="sort">Sort by: </label>
      <select id="sort" value={currentSort} onChange={handleSortChange}>
        <option value="">-Select Filter-</option>
        <option value="published_at">Date</option>
        <option value="like">Likes</option>
        <option value="hate">Hates</option>
      </select>
    </div>
  );
}

export default MovieSort;
