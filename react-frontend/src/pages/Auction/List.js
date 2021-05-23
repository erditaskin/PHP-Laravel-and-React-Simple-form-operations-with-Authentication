import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';

import { apiAuctionList } from '../../api/auction';

export default function List() {
  const [links, setLinks] = useState([]);
  const [page, setPage] = useState(1);
  const [sort, setSort] = useState('');
  const [searchTerm, setSearchTerm] = useState('');
  const [search, setSearch] = useState('');
  const [items, setItems] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    apiAuctionList({
      sort,
      page,
      searchTerm: search
    })
      .then(res => {
        setItems(res.data.data);
        setLinks(res.data.links);
        setLoading(false);
      })
      .catch(() => { });
  }, [sort, page, search]);

  const goToPage = (link) => {
    if (typeof link.url !== 'undefined' && link.url !== null && (new URL(link.url)).searchParams) {
      setPage((new URL(link.url)).searchParams.get('page'));
    }
  };


  const Paginate = () => (
    <nav aria-label="Page navigation example">
      <ul className="pagination justify-content-center">
        {links.map((link, index) => <li className={"page-item " + (link.url == null && ' disabled ') + (link.active && ' active ')} key={index}>
          <span className="page-link" onClick={() => goToPage(link)} style={{ cursor: 'pointer' }}>
            <span dangerouslySetInnerHTML={{ __html: link.label }} />
          </span>
        </li>)}
      </ul>
    </nav>
  );

  return (
    <div>
      <SearchBox sort={sort} setSort={setSort} setSearchTerm={setSearchTerm} searchTerm={searchTerm} setSearch={setSearch} setPage={setPage} />
      {!loading && (
        <React.Fragment>
          <div className="row">{items.map(item => <Item key={item.id} item={item} />)}</div>
          <Paginate />
        </React.Fragment>
      )}
    </div>
  );
}

function SearchBox({ sort, setSort, setSearchTerm, searchTerm, setSearch, setPage }) {
  const search = () => {
    setSearch(searchTerm);
    setPage(1);
  };

  return (
    <form className="row">
      <div className="col-6">
        <div className="dropdown">
          <button className="btn btn-outline-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
            Sort by Price
          </button>
          <ul className="dropdown-menu" aria-labelledby="dropdownMenuButton1">
            <li><div className={"dropdown-item " + (sort === 'asc' && ' active')} onClick={() => setSort('asc')}>Asc</div></li>
            <li><div className={"dropdown-item " + (sort === 'desc' && ' active')} onClick={() => setSort('desc')}>Desc</div></li>
          </ul>
        </div>
      </div>
      <div className="col-6">
        <div className="input-group mb-3">
          <input type="text" className="form-control" onBlur={(e) => setSearchTerm(e.target.value)} placeholder="Search term" aria-label="Search term" aria-describedby="button-addon2" />
          <button className="btn btn-secondary" type="button" id="button-addon2" onClick={() => search()}>Search</button>
        </div>
      </div>
    </form>
  );
}

function Item({ item }) {
  return (
    <div className="col-sm-3">
      <div className="card mb-5">
        <Link to={`/detail/${item.id}`}>
          <img src={item.full_image} className="card-img-top" style={{ height: '25rem' }} alt={item.name} />
        </Link>
        <div className="card-body">
          <h5 className="card-title">{item.name}</h5>
          <p className="card-text" style={{ height: '5rem' }}>{item.description}</p>
          <h4 className="card-price">Last Bid: ${item.last_price}</h4>
          <div style={{ textAlign: 'center' }}>
          <Link to={`/detail/${item.id}`} className="btn btn-warning" style={{ margin: 'auto' }}>Bid Now</Link>
          </div>
        </div>
      </div>
    </div>
  );
}